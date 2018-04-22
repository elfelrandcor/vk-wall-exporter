create table exporter
(
  groupId int          not null,
  postId  int          null,
  data    text         null,
  text    longtext     null,
  photo   varchar(255) null,
  constraint exporter_groupId_postId_uindex
  unique (groupId, postId)
)
  engine = InnoDB
  collate = utf8_unicode_ci;