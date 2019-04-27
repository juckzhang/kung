use kung;

-- 视频分类
insert into kung_video_category(source_type,`name`,create_time,update_time) values(1,'Life',484703478,1484703478),
(1,'Business',484703478,1484703478),
(1,'Movie',484703478,1484703478),
(1,'Lesson',484703478,1484703478),
(1,'Others',484703478,1484703478),
(2,'News',484703478,1484703478),
(2,'Song',484703478,1484703478),
(2,'Story',484703478,1484703478),
(2,'Movie',484703478,1484703478),
(2,'Others',484703478,1484703478);

-- 视频资源
insert into kung_video(`cate_id`, `source_type`, `is_recommd`,`title`,`sub_title`,`create_time`,`update_time`) values(1,1,1,'Life','Life描述',484703478,1484703478),
(2,1,1,'Business','Business描述',484703478,1484703478),
(3,1,1,'Movie','Movie描述',484703478,1484703478),
(4,1,1,'Lesson','Lesson描述',484703478,1484703478),
(5,1,1,'Others','Others描述',484703478,1484703478),
(6,2,1,'News','News描述',484703478,1484703478),
(7,2,1,'Song','Song描述',484703478,1484703478),
(8,2,1,'Story','Story描述',484703478,1484703478),
(9,2,1,'Movie','Movie描述',484703478,1484703478),
(10,2,1,'Others','Others描述',484703478,1484703478);

-- 评论
insert into kung_video_lines(source_id,lang_type,line_number,content,start_time,end_time) values (1,'zh_CN',1,'content','1:2:1','1:2:2'),
(1,'en_US',1,'content','1:2:1','1:2:2'),
(1,'zh_CN',2,'content2','3:2:1','3:2:2'),
(1,'en_US',2,'content2','3:2:1','3:2:2');

--