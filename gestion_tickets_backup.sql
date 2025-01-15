-- PostgreSQL database dump

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2

-- Started on 2025-01-11 14:50:44

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Aquí eliminamos CREATE DATABASE y \connect
-- Ya no necesitas crear la base de datos ni conectarte a ella.

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16446)
-- Name: tickets; Type: TABLE; Schema: public; Owner: gestion_tickets_ntnf_user
--

CREATE TABLE public.tickets (
    id integer NOT NULL,
    fecha date NOT NULL,
    serie character varying(10) NOT NULL,
    estado character varying(20) NOT NULL,
    nombre character varying(100) NOT NULL,
    tecnico character varying(50) NOT NULL,
    prioridad character varying(50) NOT NULL,
    asunto character varying(255) NOT NULL,
    problema text NOT NULL,
    solucion text,
    tiempo_solucion time without time zone NOT NULL,
    hora_creacion time without time zone NOT NULL
);

ALTER TABLE public.tickets OWNER TO gestion_tickets_ntnf_user;

--
-- TOC entry 217 (class 1259 OID 16445)
-- Name: tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: gestion_tickets_ntnf_user
--

CREATE SEQUENCE public.tickets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.tickets_id_seq OWNER TO gestion_tickets_ntnf_user;

--
-- TOC entry 4807 (class 0 OID 0)
-- Dependencies: 217
-- Name: tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER SEQUENCE public.tickets_id_seq OWNED BY public.tickets.id;

--
-- TOC entry 220 (class 1259 OID 16455)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: gestion_tickets_ntnf_user
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) NOT NULL
);

ALTER TABLE public.usuarios OWNER TO gestion_tickets_ntnf_user;

--
-- TOC entry 219 (class 1259 OID 16454)
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: gestion_tickets_ntnf_user
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.usuarios_id_seq OWNER TO gestion_tickets_ntnf_user;

--
-- TOC entry 4808 (class 0 OID 0)
-- Dependencies: 219
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;

--
-- TOC entry 4644 (class 2604 OID 16449)
-- Name: tickets id; Type: DEFAULT; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER TABLE ONLY public.tickets ALTER COLUMN id SET DEFAULT nextval('public.tickets_id_seq'::regclass);

--
-- TOC entry 4645 (class 2604 OID 16458)
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);

--
-- TOC entry 4798 (class 0 OID 16446)
-- Dependencies: 218
-- Data for Name: tickets; Type: TABLE DATA; Schema: public; Owner: gestion_tickets_ntnf_user
--

COPY public.tickets (id, fecha, serie, estado, nombre, tecnico, prioridad, asunto, problema, solucion, tiempo_solucion, hora_creacion) FROM stdin;
31	2024-12-17	TK13U2	Resuelto	CS533-E	Fran	Alta	NO DA SEÑAL GPS	Equipo GPS sin transmitir	Se envio un tecnico al sitio y se conecto la tierra al modulo	03:00:00	00:00:00

\.

--
-- TOC entry 4800 (class 0 OID 16455)
-- Dependencies: 220
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: gestion_tickets_ntnf_user
--

COPY public.usuarios (id, username, password, role) FROM stdin;
1	admin	admin	admin
2	usuario	usuario	user
\.

--
-- TOC entry 4809 (class 0 OID 0)
-- Dependencies: 217
-- Name: tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gestion_tickets_ntnf_user
--

SELECT pg_catalog.setval('public.tickets_id_seq', 1, false);

--
-- TOC entry 4810 (class 0 OID 0)
-- Dependencies: 219
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gestion_tickets_ntnf_user
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 1, false);

--
-- TOC entry 4647 (class 2606 OID 16453)
-- Name: tickets tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_pkey PRIMARY KEY (id);

--
-- TOC entry 4649 (class 2606 OID 16462)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: gestion_tickets_ntnf_user
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);

--
-- Completed on 2025-01-11 14:50:44
--

-- PostgreSQL database dump complete
