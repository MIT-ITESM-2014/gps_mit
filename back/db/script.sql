
CREATE SEQUENCE public.company_id_seq;

CREATE TABLE public.company (
                id INTEGER NOT NULL DEFAULT nextval('public.company_id_seq'),
                name VARCHAR,
                has_file_in_process INTEGER NOT NULL,
                route_count BIGINT,
                average_speed DOUBLE PRECISION,
                average_stop_count_per_trip REAL,
                average_trip_distance DOUBLE PRECISION,
                average_stem_distance DOUBLE PRECISION,
                short_stop_time DOUBLE PRECISION,
                traveling_time DOUBLE PRECISION,
                resting_time DOUBLE PRECISION,
                time_radius_short_stop REAL,
                distance_radius_short_stop REAL,
                time_radius_long_stop REAL,
                distance_radius_long_stop REAL,
                distance_traveled DOUBLE PRECISION,
                average_short_stop_duration DOUBLE PRECISION,
                average_trip_duration DOUBLE PRECISION,
                average_trip_stop_time DOUBLE PRECISION,
                average_trip_traveling_time DOUBLE PRECISION,
                average_stop_count_per_trip_sd DOUBLE PRECISION,
                average_trip_distance_sd DOUBLE PRECISION,
                average_stem_distance_sd DOUBLE PRECISION,
                average_speed_sd DOUBLE PRECISION,
                average_trip_duration_sd DOUBLE PRECISION,
                average_trip_stop_time_sd DOUBLE PRECISION,
                average_trip_traveling_time_sd DOUBLE PRECISION,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT company_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.company_id_seq OWNED BY public.company.id;

CREATE SEQUENCE public.long_stop_id_seq;

CREATE TABLE public.long_stop (
                id BIGINT NOT NULL DEFAULT nextval('public.long_stop_id_seq'),
                latitude DOUBLE PRECISION NOT NULL,
                longitude DOUBLE PRECISION NOT NULL,
                start_time TIMESTAMP,
                end_time TIMESTAMP,
                duration BIGINT,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT long_stop_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.long_stop_id_seq OWNED BY public.long_stop.id;

CREATE SEQUENCE public.uploaded_file_id_seq;

CREATE TABLE public.uploaded_file (
                id BIGINT NOT NULL DEFAULT nextval('public.uploaded_file_id_seq'),
                filename VARCHAR(20),
                step INTEGER NOT NULL,
                company_id INTEGER NOT NULL,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                CONSTRAINT uploaded_file_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.uploaded_file_id_seq OWNED BY public.uploaded_file.id;

CREATE SEQUENCE public.identity_id_seq;

CREATE TABLE public.identity (
                id INTEGER NOT NULL DEFAULT nextval('public.identity_id_seq'),
                name VARCHAR NOT NULL,
                last_name VARCHAR NOT NULL,
                username VARCHAR NOT NULL,
                password VARCHAR(40) NOT NULL,
                is_admin INTEGER,
                created_at TIMESTAMP,
                updated_at TIMESTAMP NOT NULL,
                CONSTRAINT identity_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.identity_id_seq OWNED BY public.identity.id;

CREATE SEQUENCE public.identity_company_id_seq;

CREATE TABLE public.identity_company (
                id INTEGER NOT NULL DEFAULT nextval('public.identity_company_id_seq'),
                identity_id INTEGER NOT NULL,
                company_id INTEGER NOT NULL,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                CONSTRAINT identity_company_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.identity_company_id_seq OWNED BY public.identity_company.id;

CREATE SEQUENCE public.truck_id_seq;

CREATE TABLE public.truck (
                id BIGINT NOT NULL DEFAULT nextval('public.truck_id_seq'),
                name VARCHAR NOT NULL,
                company_id INTEGER,
                total_distance DOUBLE PRECISION,
                route_count INTEGER,
                average_duration REAL,
                average_speed REAL,
                average_stop_count_per_trip REAL,
                average_distance_between_short_stops DOUBLE PRECISION,
                average_stem_distance DOUBLE PRECISION,
                average_trip_distance DOUBLE PRECISION,
                short_stops_time BIGINT,
                traveling_time BIGINT,
                resting_time BIGINT,
                stops_between_0_5 BIGINT,
                stops_between_5_15 BIGINT,
                stops_between_15_30 BIGINT,
                stops_between_30_60 BIGINT,
                stops_between_60_120 BIGINT,
                stops_between_120_plus BIGINT,
                average_trip_stop_time DOUBLE PRECISION,
                average_trip_traveling_time DOUBLE PRECISION,
                average_stop_count_per_trip_sd DOUBLE PRECISION,
                average_trip_distance_sd DOUBLE PRECISION,
                average_stem_distance_sd DOUBLE PRECISION,
                average_speed_sd DOUBLE PRECISION,
                average_trip_duration_sd DOUBLE PRECISION,
                average_trip_stop_time_sd DOUBLE PRECISION,
                average_trip_traveling_time_sd VARCHAR,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT truck_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.truck_id_seq OWNED BY public.truck.id;

CREATE SEQUENCE public.sampling_id_seq;

CREATE TABLE public.sampling (
                id INTEGER NOT NULL DEFAULT nextval('public.sampling_id_seq'),
                name VARCHAR,
                truck_id BIGINT,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                aux1 REAL,
                aux2 DOUBLE PRECISION,
                aux3 VARCHAR,
                CONSTRAINT sampling_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.sampling_id_seq OWNED BY public.sampling.id;

CREATE SEQUENCE public.route_id_seq;

CREATE TABLE public.route (
                id BIGINT NOT NULL DEFAULT nextval('public.route_id_seq'),
                name VARCHAR NOT NULL,
                beginning_stop_id BIGINT,
                end_stop_id BIGINT,
                truck_id BIGINT,
                distance REAL,
                average_speed REAL,
                short_stops_count INTEGER,
                time BIGINT,
                first_stem_distance DOUBLE PRECISION,
                first_stem_time DOUBLE PRECISION,
                second_stem_distance DOUBLE PRECISION,
                second_stem_time DOUBLE PRECISION,
                short_stops_time DOUBLE PRECISION,
                traveling_time DOUBLE PRECISION,
                stops_between_0_5 INTEGER,
                stops_between_5_15 INTEGER,
                stops_between_15_30 INTEGER,
                stops_between_30_60 INTEGER,
                stops_between_60_120 INTEGER,
                stops_between_120_plus INTEGER,
                average_short_stop_duration DOUBLE PRECISION,
                is_valid INTEGER,
                fuel_consumption DOUBLE PRECISION,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT route_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.route_id_seq OWNED BY public.route.id;

CREATE SEQUENCE public.short_stop_id_seq;

CREATE TABLE public.short_stop (
                id BIGINT NOT NULL DEFAULT nextval('public.short_stop_id_seq'),
                route_id BIGINT,
                latitude VARCHAR NOT NULL,
                longitude DOUBLE PRECISION NOT NULL,
                start_time TIMESTAMP,
                end_time TIMESTAMP,
                distance_to_next_stop DOUBLE PRECISION,
                duration DOUBLE PRECISION,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT short_stop_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.short_stop_id_seq OWNED BY public.short_stop.id;

CREATE SEQUENCE public.sample_id_seq;

CREATE TABLE public.sample (
                id BIGINT NOT NULL DEFAULT nextval('public.sample_id_seq'),
                latitude DOUBLE PRECISION NOT NULL,
                longitude DOUBLE PRECISION NOT NULL,
                datetime TIMESTAMP NOT NULL,
                route_id BIGINT,
                truck_id BIGINT,
                truck_name VARCHAR,
                interval DOUBLE PRECISION,
                distance DOUBLE PRECISION,
                speed DOUBLE PRECISION,
                status_id INTEGER NOT NULL,
                sampling_id INTEGER,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL,
                aux1 REAL,
                aux2 REAL,
                aux3 VARCHAR,
                CONSTRAINT sample_pk PRIMARY KEY (id)
);


ALTER SEQUENCE public.sample_id_seq OWNED BY public.sample.id;

ALTER TABLE public.truck ADD CONSTRAINT company_truck_fk
FOREIGN KEY (company_id)
REFERENCES public.company (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.identity_company ADD CONSTRAINT company_identity_company_fk
FOREIGN KEY (company_id)
REFERENCES public.company (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.uploaded_file ADD CONSTRAINT company_uploaded_file_fk
FOREIGN KEY (company_id)
REFERENCES public.company (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.route ADD CONSTRAINT long_stop_route_fk
FOREIGN KEY (end_stop_id)
REFERENCES public.long_stop (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.route ADD CONSTRAINT long_stop_route_fk1
FOREIGN KEY (beginning_stop_id)
REFERENCES public.long_stop (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.identity_company ADD CONSTRAINT identity_identity_company_fk
FOREIGN KEY (identity_id)
REFERENCES public.identity (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.sample ADD CONSTRAINT truck_sample_fk
FOREIGN KEY (truck_id)
REFERENCES public.truck (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.route ADD CONSTRAINT truck_route_fk
FOREIGN KEY (truck_id)
REFERENCES public.truck (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.sampling ADD CONSTRAINT truck_sampling_fk
FOREIGN KEY (truck_id)
REFERENCES public.truck (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.sample ADD CONSTRAINT sampling_sample_fk
FOREIGN KEY (sampling_id)
REFERENCES public.sampling (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.sample ADD CONSTRAINT route_sample_fk
FOREIGN KEY (route_id)
REFERENCES public.route (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.short_stop ADD CONSTRAINT route_short_stop_fk
FOREIGN KEY (route_id)
REFERENCES public.route (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
