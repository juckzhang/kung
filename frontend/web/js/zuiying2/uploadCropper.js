window.onload = function () {
    (function (factory) {
        if (typeof define === 'function' && define.amd) {
            define(['jquery'], factory);
        } else if (typeof exports === 'object') {
            factory(require('jquery'));
        } else {
            factory(jQuery);
        }
    })(function ($) {
        'use strict';
        var console = window.console || {
                log: function () {
                }
            };

        function CropAvatar($element) {
            this.$container = $element;
            this.$avatarBtn = $element; //点击按钮的div
            this.$avatarView = this.$container.parent('[name="avatar_box"]').find('[name="avatar_view"]'); //点击按钮的div
            this.$avatar = this.$avatarView.find('img');   //被点击图片的img
            this.$avatarModal = $("body").find('#popAddAlbumPic');
            this.$loading = $("#page-wrapper").find('.loading');
            this.$avatarForm = this.$avatarModal.find('[name="avatar_form"]');//点击选择文件的form
            this.$avatarUpload = this.$avatarForm.find('[name="avatar_upload"]');//input-file 的box
            this.$avatarSrc = this.$avatarForm.find('[name="avatar_src"]');//input-file
            this.$avatarData = this.$avatarForm.find('[name="avatar_data"]');//input-file
            this.$avatarInput = this.$avatarForm.find('[name="avatar_file"]');//input-file
            this.$avatarSave = this.$avatarForm.find('[name="avatar_save"]');//提交按钮
            this.$avatarBtns = this.$avatarForm.find('[name="avatar_btns"]');//各种按钮的div
            this.$avatarWrapper = this.$avatarModal.find('[name="avatar_wrapper"]');//上传文件以后，新图片显示的位置
            this.$avatarPreview = this.$avatarModal.find('[name="avatar_preview"]');//上传文件后，示意图位置
            this.init();
            // 被点击图片的路径
            console.log("被点击图片的路径："+this.$avatar.attr('src'));
            console.log(this.$avatar.prop("outerHTML"));
            this.$avatarModal.find('.avatar-wrapper').append(this.$avatar.prop("outerHTML"));
            this.$avatarModal.find('.preview-lg').append(this.$avatar.prop("outerHTML"));
            this.$avatarModal.find('.preview-md').append(this.$avatar.prop("outerHTML"));
            this.$avatarModal.find('.preview-sm').append(this.$avatar.prop("outerHTML"));
        }

        CropAvatar.prototype = {
            constructor: CropAvatar,
            support: {
                fileList: !!$('<input type="file">').prop('files'),
                blobURLs: !!window.URL && URL.createObjectURL,
                formData: !!window.FormData
            },
            init: function () {
                this.support.datauri = this.support.fileList && this.support.blobURLs;
                if (!this.support.formData) {
                    this.initIframe();
                }
                this.initTooltip();
                this.initModal();
                this.addListener();
            },
            addListener: function () {
                this.$avatarBtn.on('click', $.proxy(this.click, this));
                this.$avatarInput.on('change', $.proxy(this.change, this));
                this.$avatarForm.on('submit', $.proxy(this.submit, this));
                this.$avatarBtns.on('click', $.proxy(this.rotate, this));
            },
            initTooltip: function () {
                this.$avatarBtn.tooltip({placement: 'bottom'});
            },
            initModal: function () {
                this.$avatarModal.modal({show: false});
            },
            initPreview: function () {
                // var url = this.$avatar.attr('src');
                // var url = this.parent('[name="avatar_box"]').find('[name="avatar_view"]').find('img').attr('src');
                console.log(this);
                // this.$avatarPreview.empty().html('<img src="' + url + '">');
            },
            initIframe: function () {
                var target = 'upload-iframe-' + (new Date()).getTime(), $iframe = $('<iframe>').attr({
                    name: target,
                    src: ''
                }), _this = this;
                $iframe.one('load', function () {
                    $iframe.on('load', function () {
                        var data;
                        try {
                            data = $(this).contents().find('body').text();
                        } catch (e) {
                            console.log(e.message);
                        }
                        if (data) {
                            try {
                                data = $.parseJSON(data);
                            } catch (e) {
                                console.log(e.message);
                            }
                            _this.submitDone(data);
                        } else {
                            _this.submitFail('Image upload failed!');
                        }
                        _this.submitEnd();
                    });
                });
                this.$iframe = $iframe;
                this.$avatarForm.attr('target', target).after($iframe.hide());
            },
            click: function () {
                console.log("click");
                this.$avatarModal.modal('show');
                this.initPreview();
            },
            change: function (e) {
                var files, file;
                if (this.support.datauri) {
                    files = this.$avatarInput.prop('files');
                    if (files.length > 0) {
                        file = files[0];
                        if (this.isImageFile(file)) {
                            if (this.url) {
                                URL.revokeObjectURL(this.url);
                            }
                            this.url = URL.createObjectURL(file);
                            this.startCropper();
                        }
                    }

                } else {
                    file = this.$avatarInput.val();
                    if (this.isImageFile(file)) {
                        this.syncUpload();
                    }
                }
                /*var filemaxsize = 1024 * 5;//5M
                var target = $(e.target);
                var Size = target[0].files[0].size / 1024;
                if(Size > filemaxsize) {
                    alert('图片过大，请重新选择!');
                    $(".avatar-wrapper").childre().remove;
                    return false;
                }
                if(!this.files[0].type.match(/image.*!/)) {
                    alert('请选择正确的图片!')
                } else {
                    var filename = document.querySelector("#avatar-name");
                    var texts = document.querySelector("#avatarInput").value;
                    var teststr = texts; //你这里的路径写错了
                    var testend = teststr.match(/[^\\]+\.[^\(]+/i); //直接完整文件名的
                    filename.innerHTML = testend;
                }*/

            },
            submit: function () {
                if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
                    return false;
                }
                if (this.support.formData) {
                    this.ajaxUpload();
                    return false;
                }
            },
            rotate: function (e) {
                var data;
                if (this.active) {
                    data = $(e.target).data();
                    if (data.method) {
                        this.$img.cropper(data.method, data.option);
                    }
                }
            },
            isImageFile: function (file) {
                if (file.type) {
                    return /^image\/\w+$/.test(file.type);
                } else {
                    return /\.(jpg|jpeg|png|gif)$/.test(file);
                }
            },
            startCropper: function () {
                var _this = this;
                if (this.active) {
                    this.$img.cropper('replace', this.url);
                } else {
                    this.$img = $('<img src="' + this.url + '">');
                    this.$avatarWrapper.empty().html(this.$img);
                    this.$img.cropper({
                        aspectRatio: 16 / 9,
                        preview: this.$avatarPreview.selector,
                        strict: false,
                        crop: function (data) {
                            var json = ['{"x":' + data.x, '"y":' + data.y, '"height":' + data.height, '"width":' + data.width, '"rotate":' + data.rotate + '}'].join();
                            _this.$avatarData.val(json);
                        }
                    });
                    this.active = true;
                }
            },
            stopCropper: function () {
                if (this.active) {
                    this.$img.cropper('destroy');
                    this.$img.remove();
                    this.active = false;
                }
            },
            ajaxUpload: function () {
                var url = this.$avatarForm.attr('action'), data = new FormData(this.$avatarForm[0]), _this = this;
                console.log(url);

                _this.submitDone(data);
                /*$.ajax(url, {
                    headers: {'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        _this.submitStart();
                    },
                    success: function (data) {
                        _this.submitDone(data);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(0)
                        var canvasdata = $image.cropper("getCanvasData");
                        var cropBoxData = $image.cropper('getCropBoxData');
                        console.log(canvasdata,cropBoxData)
                        _this.submitFail(textStatus || errorThrown);
                    },
                    complete: function () {
                        _this.submitEnd();
                    }
                });*/
            },
            syncUpload: function () {
                this.$avatarSave.click();
            },
            submitStart: function () {
                this.$loading.fadeIn();
            },
            submitDone: function (data) {
                alert(0)
                console.log(this.$avatarView);
                function convertToData(url, canvasdata, cropdata, callback) {
                    var cropw = cropdata.width; // 剪切的宽
                    var croph = cropdata.height; // 剪切的宽
                    var imgw = canvasdata.width; // 图片缩放或则放大后的高
                    var imgh = canvasdata.height; // 图片缩放或则放大后的高
                    var poleft = canvasdata.left - cropdata.left; // canvas定位图片的左边位置
                    var potop = canvasdata.top - cropdata.top; // canvas定位图片的上边位置
                    var canvas = document.createElement("canvas");
                    var ctx = canvas.getContext('2d');
                    canvas.width = cropw;
                    canvas.height = croph;
                    var img = new Image();
                    img.src = url;
                    img.onload = function() {
                        this.width = imgw;
                        this.height = imgh;
                        // 这里主要是懂得canvas与图片的裁剪之间的关系位置
                        ctx.drawImage(this, poleft, potop, this.width, this.height);
                        var base64 = canvas.toDataURL('image/jpg', 1); // 这里的“1”是指的是处理图片的清晰度（0-1）之间，当然越小图片越模糊，处理后的图片大小也就越小
                        callback && callback(base64) // 回调base64字符串
                    }
                }
                var $image = $('.avatar-wrapper > img');

                $image.on("load", function() { // 等待图片加载成功后，才进行图片的裁剪功能
                    $image.cropper({
                        aspectRatio: 1 / 1　　 // 1：1的比例进行裁剪，可以是任意比例，自己调整

                    });
                });

                var src = $image.eq(0).attr("src");
                var canvasdata = $image.cropper("getCanvasData");
                var cropBoxData = $image.cropper('getCropBoxData');

                console.log(canvasdata,cropBoxData);
                convertToData(src, canvasdata, cropBoxData, function(basechar) {
                    // 回调后的函数处理
                    console.log(basechar);
                    // this.$avatar.attr("src", basechar);
                    // $('.s-c-pic').find('.s-c-pic-in').find("img").attr("src", basechar)
                });
                if ($.isPlainObject(data)) {
                    if (data.result) {
                        this.url = data.result;
                        if (this.support.datauri || this.uploaded) {
                            this.uploaded = false;
                            this.cropDone();
                        } else {
                            this.uploaded = true;
                            this.$avatarSrc.val(this.url);
                            this.startCropper();
                        }
                        this.$avatarInput.val('');
                    } else if (data.message) {
                        this.alert(data.message);
                    }
                } else {
                    this.alert('服务器未响应');
                }
            },
            submitFail: function (msg) {
                this.alert(msg);
            },
            submitEnd: function () {
                this.$loading.fadeOut();
            },
            cropDone: function () {
                this.$avatarForm.get(0).reset();
                this.$avatar.attr('src', this.url);
                this.stopCropper();
                this.$avatarModal.modal('hide');
            },
            alert: function (msg) {
                var $alert = ['<div>', msg, '</div>'].join('');
                this.$avatarUpload.after($alert);
            }
        };
        $(function () {
            var btnUploadPagePic = $('[name="btnUploadPagePic"]');
            btnUploadPagePic.click(function () {
                var _this=this;
                console.log(_this)
                return new CropAvatar(_this);
            });
            // return new CropAvatar($('[name="btnUploadPagePic"]'));
        });
    })
};
