/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     19/02/2026 11:07:19                          */
/*==============================================================*/


drop table if exists ADMIN;

drop table if exists BERITA;

drop table if exists DETAIL_PEMINJAMAN_SARANA;

drop table if exists GAMBAR_DASHBOARD;

drop table if exists GEDUNG;

drop table if exists PEMINJAMAN_TRANSAKSI;

drop table if exists PROFIL;

drop table if exists RUANGAN;

drop table if exists SARANA;

drop table if exists TENTANG;

drop table if exists USERS;

/*==============================================================*/
/* Table: ADMIN                                                 */
/*==============================================================*/
create table ADMIN
(
   EMAIL_ADMIN          varchar(128) not null,
   ID_PROFIL            int,
   ID_GEDUNG            int,
   NAME_ADMIN           char(128),
   PASSWORD_ADMIN       char(64),
   NO_HP_ADMIN          char(15),
   ROLE                 char(24),
   primary key (EMAIL_ADMIN)
);

/*==============================================================*/
/* Table: BERITA                                                */
/*==============================================================*/
create table BERITA
(
   ID_BERITA            int not null,
   EMAIL_ADMIN          varchar(128),
   JUDUL_BERITA         char(128),
   SLUG                 char(64),
   ISI_BERITA           text,
   GAMBAR_BERITA        varchar(255),
   TANGGAL_PUBLISH      date,
   STATUS_BERITA        char(25),
   CREATED_AT           timestamp,
   UPDATED_AT           timestamp,
   primary key (ID_BERITA)
);

/*==============================================================*/
/* Table: DETAIL_PEMINJAMAN_SARANA                              */
/*==============================================================*/
create table DETAIL_PEMINJAMAN_SARANA
(
   ID_DETAIL_PEMINJAMAN int not null,
   ID_SARANA            int,
   ID_PEMINJAMAN        int,
   JUMLAH               char(5),
   primary key (ID_DETAIL_PEMINJAMAN)
);

/*==============================================================*/
/* Table: GAMBAR_DASHBOARD                                      */
/*==============================================================*/
create table GAMBAR_DASHBOARD
(
   ID_GAMBAR            int not null,
   EMAIL_ADMIN          varchar(128),
   POSISI_GAMBAR        bool,
   PATH_GAMBAR          varchar(255),
   WAKTU_UPLOAD         timestamp,
   primary key (ID_GAMBAR)
);

/*==============================================================*/
/* Table: GEDUNG                                                */
/*==============================================================*/
create table GEDUNG
(
   ID_GEDUNG            int not null,
   EMAIL_ADMIN          varchar(128),
   NAMA_GEDUNG          char(64),
   KORDINAT_GEDUNG_Y    varchar(255),
   KORDINAT_GEDUNG_X    varchar(255),
   LOKASI               text,
   primary key (ID_GEDUNG)
);

/*==============================================================*/
/* Table: PEMINJAMAN_TRANSAKSI                                  */
/*==============================================================*/
create table PEMINJAMAN_TRANSAKSI
(
   ID_PEMINJAMAN        int not null,
   EMAIL_USERS          varchar(128),
   ID_RUANGAN           int,
   EMAIL_ADMIN          varchar(128),
   TGL_PEMINJAMAN       date,
   WAKTU_MULAI          time,
   WAKTU_SELESAI        time,
   STATUS_PEMINJAMAN    char(32),
   STATUS_SARANA        char(32),
   primary key (ID_PEMINJAMAN)
);

/*==============================================================*/
/* Table: PROFIL                                                */
/*==============================================================*/
create table PROFIL
(
   ID_PROFIL            int not null,
   EMAIL_ADMIN          varchar(128),
   EMAIL_USERS          varchar(128),
   FOTO_PROFIL          varchar(255),
   JENIS_KELAMIN        bool,
   ALAMAT_USERS         text,
   TANGGAL_LAHIR        date,
   primary key (ID_PROFIL)
);

/*==============================================================*/
/* Table: RUANGAN                                               */
/*==============================================================*/
create table RUANGAN
(
   ID_RUANGAN           int not null,
   ID_GEDUNG            int,
   NAMA_RUANGAN         char(64),
   primary key (ID_RUANGAN)
);

/*==============================================================*/
/* Table: SARANA                                                */
/*==============================================================*/
create table SARANA
(
   ID_SARANA            int not null,
   NAMA_SARANA          char(128),
   KONDISI_SARANA       char(32),
   TGL_PENERIMAAN       date,
   STOK                 char(5),
   primary key (ID_SARANA)
);

/*==============================================================*/
/* Table: TENTANG                                               */
/*==============================================================*/
create table TENTANG
(
   ID_TENTANG           int not null,
   NAMA_INSTANSI        char(64),
   KORDINAT_X           varchar(255),
   KORDINAT_Y           varchar(255),
   NO_HP                char(15),
   KANTOR               char(15),
   EMAIL_TENTANG        char(128),
   LOGO_INSTANSI        varchar(255),
   FOTO_INSTANSI        varchar(255),
   LINK_GOOGLE_MAPS     text,
   primary key (ID_TENTANG)
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS
(
   EMAIL_USERS          varchar(128) not null,
   ID_PROFIL            int,
   NAME_USERS           char(128),
   PASSWORD_USERS       char(64),
   NO_HP_USERS          char(15),
   primary key (EMAIL_USERS)
);

alter table ADMIN add constraint FK_RELATIONSHIP_1 foreign key (ID_PROFIL)
      references PROFIL (ID_PROFIL) on delete restrict on update restrict;

alter table ADMIN add constraint FK_RELATIONSHIP_14 foreign key (ID_GEDUNG)
      references GEDUNG (ID_GEDUNG) on delete restrict on update restrict;

alter table BERITA add constraint FK_RELATIONSHIP_4 foreign key (EMAIL_ADMIN)
      references ADMIN (EMAIL_ADMIN) on delete restrict on update restrict;

alter table DETAIL_PEMINJAMAN_SARANA add constraint FK_RELATIONSHIP_13 foreign key (ID_PEMINJAMAN)
      references PEMINJAMAN_TRANSAKSI (ID_PEMINJAMAN) on delete restrict on update restrict;

alter table DETAIL_PEMINJAMAN_SARANA add constraint FK_RELATIONSHIP_7 foreign key (ID_SARANA)
      references SARANA (ID_SARANA) on delete restrict on update restrict;

alter table GAMBAR_DASHBOARD add constraint FK_RELATIONSHIP_3 foreign key (EMAIL_ADMIN)
      references ADMIN (EMAIL_ADMIN) on delete restrict on update restrict;

alter table GEDUNG add constraint FK_RELATIONSHIP_9 foreign key (EMAIL_ADMIN)
      references ADMIN (EMAIL_ADMIN) on delete restrict on update restrict;

alter table PEMINJAMAN_TRANSAKSI add constraint FK_RELATIONSHIP_10 foreign key (EMAIL_ADMIN)
      references ADMIN (EMAIL_ADMIN) on delete restrict on update restrict;

alter table PEMINJAMAN_TRANSAKSI add constraint FK_RELATIONSHIP_5 foreign key (EMAIL_USERS)
      references USERS (EMAIL_USERS) on delete restrict on update restrict;

alter table PEMINJAMAN_TRANSAKSI add constraint FK_RELATIONSHIP_8 foreign key (ID_RUANGAN)
      references RUANGAN (ID_RUANGAN) on delete restrict on update restrict;

alter table PROFIL add constraint FK_RELATIONSHIP_11 foreign key (EMAIL_ADMIN)
      references ADMIN (EMAIL_ADMIN) on delete restrict on update restrict;

alter table PROFIL add constraint FK_RELATIONSHIP_2 foreign key (EMAIL_USERS)
      references USERS (EMAIL_USERS) on delete restrict on update restrict;

alter table RUANGAN add constraint FK_RELATIONSHIP_6 foreign key (ID_GEDUNG)
      references GEDUNG (ID_GEDUNG) on delete restrict on update restrict;

alter table USERS add constraint FK_RELATIONSHIP_12 foreign key (ID_PROFIL)
      references PROFIL (ID_PROFIL) on delete restrict on update restrict;

