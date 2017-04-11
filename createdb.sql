 CREATE TABLE USER( 
 ID BIGINT NOT NULL AUTO_INCREMENT,
   USERNAME VARCHAR (30)            NOT NULL,
   EMAIL  VARCHAR(48)               NOT NULL,
   PASSWORD  VARCHAR (64) ,         NOT NULL,
   ROLE   ENUM ('admin', 'member')  NOT NULL,
   CREATION_DATE  DATE              NOT NULL,
   UNIQUE KEY (USERNAME, EMAIL),
   PRIMARY KEY (ID)
);

CREATE TABLE MESSAGE(
   ID   BIGINT                      NOT NULL AUTO_INCREMENT,
   USER_ID BIGINT                   NOT NULL,
   CONTENT  NVARCHAR                NOT NULL,
   CHAT_TIMELINE_ID  BIGINT         NOT NULL,
   CREATION_DATE  TIMESTAMP         NOT NULL,       
   PRIMARY KEY (ID)
);

CREATE TABLE CHAT_TIMELINE(
   ID   BIGINT                                           NOT NULL AUTO_INCREMENT,
   NAME VARCHAR (30)                                     NOT NULL,
   CHAT_TYPE ENUM  ('chat_group','chat_team','private')  NOT NULL,
   CREATION_DATE DATE                                    NOT NULL,
   PRIMARY KEY (ID)
);

CREATE TABLE USER_CHAT_TIMELINE(
   USER_ID BIGINT                   NOT NULL,
   CHAT_TIMELINE_ID BIGINT,         NOT NULL,
);
