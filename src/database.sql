
DROP TABLE IF EXISTS "notas";
CREATE TABLE "public"."notas" (
    "icolegio" smallint NOT NULL,
    "codigo" character varying(18) NOT NULL,
    "imateria" character varying(8) NOT NULL,
    "iperiodo" smallint NOT NULL,
    "fallas" smallint,
    "igrado" smallint,
    "igrupo" character varying(3),
    CONSTRAINT "notas_pkey" PRIMARY KEY ("icolegio", "codigo", "imateria", "iperiodo")
) WITH (oids = false);

INSERT INTO "notas" ("icolegio", "codigo", "imateria", "iperiodo", "fallas", "igrado", "igrupo") VALUES
(277,	'1087546265',	'Matemati',	1,	0,	10,	'01');
