--
-- PostgreSQL database dump
--

\restrict LAX0b2p2bTkEWHhDwIHTcff9J1KiGRUvLpqO1bCZ0e8R2ZvEOG4iO6hvT7Wopmu

-- Dumped from database version 13.23 (Debian 13.23-1.pgdg13+1)
-- Dumped by pg_dump version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)

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

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


--
-- Name: doctype_field_type_enum; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.doctype_field_type_enum AS ENUM (
    'text',
    'range',
    'select',
    'label',
    'date',
    'dateRange',
    'dateTimeRange',
    'timeRange',
    'switch',
    'uploadFile',
    'comment'
);


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: _d_additional_equipment_brands; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_additional_equipment_brands (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_additional_equipment_models; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_additional_equipment_models (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    brand_id bigint NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_additional_equipment_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_additional_equipment_types (
    id bigint NOT NULL,
    key character varying(255),
    name jsonb NOT NULL
);


--
-- Name: _d_adrs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_adrs (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_cargo_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_cargo_types (
    id bigint NOT NULL,
    key character varying(255),
    name jsonb NOT NULL
);


--
-- Name: _d_cell_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_cell_statuses (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name character varying(30) NOT NULL
);


--
-- Name: _d_company_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_company_categories (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(50) NOT NULL,
    creator_company_id uuid
);


--
-- Name: _d_company_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_company_statuses (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_company_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_company_types (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    short_name character varying(20) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_container_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_container_types (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    key character varying(255) NOT NULL
);


--
-- Name: _d_countries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_countries (
    id bigint NOT NULL,
    key character varying(30) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_delivery_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_delivery_types (
    id bigint NOT NULL,
    key character varying(255),
    name jsonb NOT NULL
);


--
-- Name: _d_doctype_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_doctype_statuses (
    id bigint NOT NULL,
    key character varying(30) NOT NULL,
    name jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: _d_document_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_document_statuses (
    id bigint NOT NULL,
    key character varying(70) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_download_zones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_download_zones (
    id bigint NOT NULL,
    key character varying(50) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_exception_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_exception_types (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_goods_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_goods_categories (
    id bigint NOT NULL,
    key character varying(25),
    name jsonb NOT NULL,
    parent_id integer
);


--
-- Name: _d_legal_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_legal_types (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_measurement_units; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_measurement_units (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_package_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_package_types (
    id bigint NOT NULL,
    key character varying(30),
    name jsonb NOT NULL
);


--
-- Name: _d_positions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_positions (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_regions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_regions (
    id bigint NOT NULL,
    name character varying(120) NOT NULL
);


--
-- Name: _d_register_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_register_statuses (
    id bigint NOT NULL,
    key character varying(50) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_roles (
    id bigint NOT NULL,
    name character varying(125) NOT NULL,
    title character varying(125) NOT NULL,
    guard_name character varying(125) NOT NULL,
    visible boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_company_id uuid
);


--
-- Name: _d_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public._d_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: _d_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public._d_roles_id_seq OWNED BY public._d_roles.id;


--
-- Name: _d_service_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_service_categories (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    key character varying(255) NOT NULL
);


--
-- Name: _d_settlements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_settlements (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    region_id bigint
);


--
-- Name: _d_storage_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_storage_types (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_streets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_streets (
    id bigint NOT NULL,
    name character varying(100) NOT NULL
);


--
-- Name: _d_task_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_task_types (
    id bigint NOT NULL,
    key character varying(100) NOT NULL,
    name jsonb NOT NULL,
    is_system boolean DEFAULT false NOT NULL,
    creator_company_id uuid,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: _d_task_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public._d_task_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: _d_task_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public._d_task_types_id_seq OWNED BY public._d_task_types.id;


--
-- Name: _d_transport_brands; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_brands (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_transport_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_categories (
    id bigint NOT NULL,
    key character varying(70) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_transport_downloads; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_downloads (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_transport_models; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_models (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    brand_id bigint NOT NULL,
    key character varying(40) NOT NULL
);


--
-- Name: _d_transport_planning_failure_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_planning_failure_types (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: _d_transport_planning_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_planning_statuses (
    id bigint NOT NULL,
    name jsonb NOT NULL,
    key character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: _d_transport_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_transport_types (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_user_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_user_statuses (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_warehouse_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_warehouse_types (
    id bigint NOT NULL,
    key character varying(15) NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_zone_subtypes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_zone_subtypes (
    id bigint NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_zone_subtypes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public._d_zone_subtypes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: _d_zone_subtypes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public._d_zone_subtypes_id_seq OWNED BY public._d_zone_subtypes.id;


--
-- Name: _d_zone_type_subtype; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_zone_type_subtype (
    id bigint NOT NULL,
    zone_type_id bigint NOT NULL,
    zone_subtype_id bigint NOT NULL
);


--
-- Name: _d_zone_type_subtype_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public._d_zone_type_subtype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: _d_zone_type_subtype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public._d_zone_type_subtype_id_seq OWNED BY public._d_zone_type_subtype.id;


--
-- Name: _d_zone_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public._d_zone_types (
    id bigint NOT NULL,
    name jsonb NOT NULL
);


--
-- Name: _d_zone_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public._d_zone_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: _d_zone_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public._d_zone_types_id_seq OWNED BY public._d_zone_types.id;


--
-- Name: additional_equipment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.additional_equipment (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    brand_id bigint NOT NULL,
    model_id bigint NOT NULL,
    type_id bigint NOT NULL,
    license_plate character varying(8) NOT NULL,
    download_methods json,
    length double precision NOT NULL,
    width double precision NOT NULL,
    height double precision NOT NULL,
    volume double precision NOT NULL,
    capacity_eu double precision NOT NULL,
    capacity_am double precision NOT NULL,
    adr_id bigint,
    manufacture_year integer NOT NULL,
    country_id bigint NOT NULL,
    company_id uuid NOT NULL,
    transport_id uuid NOT NULL,
    img_type character varying(5),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    carrying_capacity double precision,
    hydroboard boolean,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: additional_equipment_brands_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.additional_equipment_brands_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: additional_equipment_brands_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.additional_equipment_brands_id_seq OWNED BY public._d_additional_equipment_brands.id;


--
-- Name: additional_equipment_models_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.additional_equipment_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: additional_equipment_models_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.additional_equipment_models_id_seq OWNED BY public._d_additional_equipment_models.id;


--
-- Name: additional_equipment_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.additional_equipment_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: additional_equipment_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.additional_equipment_types_id_seq OWNED BY public._d_additional_equipment_types.id;


--
-- Name: address_details; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.address_details (
    id bigint NOT NULL,
    country_id bigint,
    settlement_id bigint,
    street_id bigint,
    building_number character varying(10),
    apartment_number character varying(5),
    legal_address character varying(50),
    gln bigint,
    deleted_at timestamp(0) without time zone,
    comment character varying(255)
);


--
-- Name: address_details_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.address_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: address_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.address_details_id_seq OWNED BY public.address_details.id;


--
-- Name: adrs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.adrs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: adrs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.adrs_id_seq OWNED BY public._d_adrs.id;


--
-- Name: barcodes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.barcodes (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    barcode character varying(30) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id uuid,
    creator_company_id uuid NOT NULL,
    entity_type character varying(255) NOT NULL,
    entity_id uuid NOT NULL
);


--
-- Name: bookmarks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bookmarks (
    id bigint NOT NULL,
    name character varying(80) NOT NULL,
    key character varying(80) NOT NULL,
    properties json,
    page_uri character varying(150) NOT NULL,
    html_id character varying(50),
    user_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bookmarks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bookmarks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bookmarks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bookmarks_id_seq OWNED BY public.bookmarks.id;


--
-- Name: cargo_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cargo_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cargo_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cargo_types_id_seq OWNED BY public._d_cargo_types.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    parent_id uuid,
    goods_category_id bigint,
    workspace_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    erp_id character varying(255)
);


--
-- Name: cell_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cell_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cell_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cell_statuses_id_seq OWNED BY public._d_cell_statuses.id;


--
-- Name: cells; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cells (
    code character varying(100) NOT NULL,
    status_id bigint,
    deleted_at timestamp(0) without time zone,
    id uuid NOT NULL,
    parent_type character varying(255),
    model_id uuid NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    CONSTRAINT cells_parent_type_check CHECK (((parent_type)::text = ANY (ARRAY[('zone'::character varying)::text, ('sector'::character varying)::text, ('row'::character varying)::text])))
);


--
-- Name: COLUMN cells.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.cells.status IS '1 = Доступно, 2 = Заблоковано';


--
-- Name: companies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.companies (
    id uuid NOT NULL,
    email character varying(255),
    company_id uuid NOT NULL,
    company_type character varying(255) NOT NULL,
    company_type_id bigint NOT NULL,
    status_id bigint DEFAULT '1'::bigint NOT NULL,
    ipn bigint,
    address_id bigint NOT NULL,
    bank character varying(255),
    iban character varying(255),
    mfo integer,
    about character varying(255),
    img_type character varying(5),
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    currency character varying(255),
    creator_id uuid,
    workspace_id bigint,
    category_id bigint,
    creator_company_id uuid,
    erp_id character varying(255)
);


--
-- Name: company_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.company_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: company_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.company_categories_id_seq OWNED BY public._d_company_categories.id;


--
-- Name: company_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.company_requests (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    company_id uuid NOT NULL,
    user_id uuid NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: company_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.company_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: company_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.company_statuses_id_seq OWNED BY public._d_company_statuses.id;


--
-- Name: company_to_workspaces; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.company_to_workspaces (
    id bigint NOT NULL,
    company_id uuid,
    workspace_id bigint
);


--
-- Name: company_to_workspaces_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.company_to_workspaces_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: company_to_workspaces_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.company_to_workspaces_id_seq OWNED BY public.company_to_workspaces.id;


--
-- Name: company_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.company_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: company_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.company_types_id_seq OWNED BY public._d_company_types.id;


--
-- Name: container_by_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.container_by_documents (
    id bigint NOT NULL,
    container_id uuid,
    document_id uuid,
    count integer NOT NULL,
    data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: container_by_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.container_by_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: container_by_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.container_by_documents_id_seq OWNED BY public.container_by_documents.id;


--
-- Name: container_registers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.container_registers (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    code character varying(255),
    container_id uuid,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cell_id uuid,
    status_id character varying(255) DEFAULT '1'::character varying NOT NULL,
    CONSTRAINT container_registers_status_id_check CHECK (((status_id)::text = ANY (ARRAY[('1'::character varying)::text, ('2'::character varying)::text, ('3'::character varying)::text])))
);


--
-- Name: container_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.container_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: container_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.container_types_id_seq OWNED BY public._d_container_types.id;


--
-- Name: containers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.containers (
    id uuid NOT NULL,
    name character varying(50) NOT NULL,
    code_format character varying(5) NOT NULL,
    weight double precision NOT NULL,
    height double precision NOT NULL,
    length double precision NOT NULL,
    width double precision NOT NULL,
    max_weight double precision NOT NULL,
    reversible smallint DEFAULT '0'::smallint NOT NULL,
    type_id bigint NOT NULL,
    creator_company_id uuid NOT NULL,
    erp_id character varying(255),
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    local_id bigint DEFAULT '1'::bigint NOT NULL
);


--
-- Name: contracts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.contracts (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    role smallint NOT NULL,
    type_id smallint NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    file character varying(255),
    termination_reasons text,
    decline_reasons text,
    company_id uuid NOT NULL,
    counterparty_id uuid NOT NULL,
    company_regulation_id uuid,
    counterparty_regulation_id uuid,
    expired_at timestamp(0) without time zone NOT NULL,
    signed_at timestamp(0) without time zone,
    signed_at_counterparty timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: contracts_comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.contracts_comments (
    id bigint NOT NULL,
    comment text NOT NULL,
    contract_id uuid NOT NULL,
    company_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: contracts_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.contracts_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contracts_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.contracts_comments_id_seq OWNED BY public.contracts_comments.id;


--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: countries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.countries_id_seq OWNED BY public._d_countries.id;


--
-- Name: delivery_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.delivery_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: delivery_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.delivery_types_id_seq OWNED BY public._d_delivery_types.id;


--
-- Name: doctype_fields; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctype_fields (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    key character varying(200) NOT NULL,
    title character varying(200) NOT NULL,
    description character varying(255),
    type character varying(255) NOT NULL,
    model character varying(255),
    parameters json,
    creator_company_id uuid,
    system boolean DEFAULT true NOT NULL,
    CONSTRAINT doctype_fields_type_check CHECK (((type)::text = ANY (ARRAY[('text'::character varying)::text, ('range'::character varying)::text, ('select'::character varying)::text, ('label'::character varying)::text, ('date'::character varying)::text, ('dateRange'::character varying)::text, ('dateTimeRange'::character varying)::text, ('timeRange'::character varying)::text, ('switch'::character varying)::text, ('uploadFile'::character varying)::text, ('comment'::character varying)::text, ('dateTime'::character varying)::text])))
);


--
-- Name: doctype_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.doctype_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: doctype_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.doctype_statuses_id_seq OWNED BY public._d_doctype_statuses.id;


--
-- Name: doctype_structure; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctype_structure (
    id bigint NOT NULL,
    kind character varying(255) NOT NULL,
    settings json NOT NULL
);


--
-- Name: doctype_structure_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.doctype_structure_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: doctype_structure_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.doctype_structure_id_seq OWNED BY public.doctype_structure.id;


--
-- Name: document_leftover_reservations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.document_leftover_reservations (
    id bigint NOT NULL,
    document_id uuid NOT NULL,
    goods_id uuid NOT NULL,
    quantity double precision NOT NULL
);


--
-- Name: document_leftover_reservations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.document_leftover_reservations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: document_leftover_reservations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.document_leftover_reservations_id_seq OWNED BY public.document_leftover_reservations.id;


--
-- Name: document_relations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.document_relations (
    id bigint NOT NULL,
    document_id uuid NOT NULL,
    related_id uuid NOT NULL
);


--
-- Name: document_relations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.document_relations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: document_relations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.document_relations_id_seq OWNED BY public.document_relations.id;


--
-- Name: document_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.document_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: document_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.document_statuses_id_seq OWNED BY public._d_document_statuses.id;


--
-- Name: document_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.document_types (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(200) NOT NULL,
    settings json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid,
    kind character varying(50)
);


--
-- Name: documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.documents (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    status_id bigint,
    type_id uuid,
    data json NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    additional_properties json,
    is_reserved boolean DEFAULT false NOT NULL,
    creator_company_id uuid NOT NULL,
    warehouse_id uuid NOT NULL,
    state character varying(255),
    created_by_system boolean DEFAULT false NOT NULL
);


--
-- Name: download_zones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.download_zones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: download_zones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.download_zones_id_seq OWNED BY public._d_download_zones.id;


--
-- Name: entity_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.entity_logs (
    id bigint NOT NULL,
    log_type character varying(255) NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL,
    data json,
    user_id uuid,
    creator_company_id uuid,
    ip_address character varying(255),
    source character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: entity_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.entity_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: entity_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.entity_logs_id_seq OWNED BY public.entity_logs.id;


--
-- Name: exception_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.exception_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: exception_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.exception_types_id_seq OWNED BY public._d_exception_types.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: file_loads; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.file_loads (
    name character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    new_name character varying(50) NOT NULL,
    user_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL,
    id uuid NOT NULL
);


--
-- Name: goods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.goods (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(50) NOT NULL,
    brand uuid,
    manufacturer uuid,
    is_batch_accounting smallint DEFAULT '0'::smallint NOT NULL,
    is_weight smallint DEFAULT '0'::smallint NOT NULL,
    weight_netto double precision,
    weight_brutto double precision,
    height double precision,
    width double precision,
    length double precision,
    temp_from double precision,
    temp_to double precision,
    humidity_from double precision,
    humidity_to double precision,
    dustiness_from double precision,
    dustiness_to double precision,
    status_id character varying(255) DEFAULT '1'::character varying NOT NULL,
    measurement_unit_id bigint,
    adr_id bigint,
    manufacturer_country_id bigint,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    provider uuid,
    img_type character varying(5),
    expiration_date json,
    category_id uuid,
    erp_id character varying(255),
    is_kit boolean DEFAULT false NOT NULL,
    CONSTRAINT goods_status_id_check CHECK (((status_id)::text = ANY (ARRAY[('1'::character varying)::text, ('2'::character varying)::text])))
);


--
-- Name: goods_by_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.goods_by_documents (
    id bigint NOT NULL,
    document_id uuid,
    count integer NOT NULL,
    data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    goods_id uuid NOT NULL
);


--
-- Name: goods_kit_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.goods_kit_items (
    id uuid NOT NULL,
    goods_parent_id uuid NOT NULL,
    goods_id uuid NOT NULL,
    package_id uuid NOT NULL,
    quantity integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: goods_to_container_registers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.goods_to_container_registers (
    id uuid NOT NULL,
    leftover_id uuid NOT NULL,
    container_register_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: income_document_leftovers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.income_document_leftovers (
    id uuid NOT NULL,
    table_id smallint NOT NULL,
    batch character varying(100),
    has_condition boolean NOT NULL,
    manufacture_date date NOT NULL,
    bb_date date NOT NULL,
    package_id uuid NOT NULL,
    container_id uuid,
    quantity integer NOT NULL,
    document_id uuid NOT NULL,
    goods_id uuid NOT NULL,
    creator_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expiration_term integer,
    local_id character varying(255) DEFAULT '1'::character varying
);


--
-- Name: integrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.integrations (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    accesses json,
    key character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: integrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.integrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: integrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.integrations_id_seq OWNED BY public.integrations.id;


--
-- Name: inventories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventories (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    local_id bigint NOT NULL,
    show_leftovers boolean DEFAULT false NOT NULL,
    restrict_goods_movement boolean DEFAULT false NOT NULL,
    process_cell integer DEFAULT 0 NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    type character varying(16) NOT NULL,
    creator_id uuid,
    warehouse_id uuid NOT NULL,
    warehouse_erp_id uuid,
    zone_id uuid,
    sector_id uuid,
    row_id uuid,
    manufacturer_id uuid,
    supplier_id uuid,
    start_date timestamp(0) without time zone,
    end_date timestamp(0) without time zone,
    comment text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    cell_id uuid,
    performer_id uuid,
    priority smallint DEFAULT '0'::smallint NOT NULL,
    category_subcategory uuid,
    erp_id character varying(255),
    brand uuid
);


--
-- Name: COLUMN inventories.priority; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.inventories.priority IS '0 = normal; higher = more important';


--
-- Name: inventories_local_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventories_local_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventories_local_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventories_local_id_seq OWNED BY public.inventories.local_id;


--
-- Name: inventory_goods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_goods (
    id bigint NOT NULL,
    inventory_id uuid NOT NULL,
    goods_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_goods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_goods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_goods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_goods_id_seq OWNED BY public.inventory_goods.id;


--
-- Name: inventory_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_items (
    id uuid NOT NULL,
    qty integer,
    real_qty integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_id uuid NOT NULL,
    inventory_id uuid NOT NULL,
    cell_id uuid,
    update_id uuid,
    status smallint DEFAULT '1'::smallint NOT NULL,
    area character varying(255)
);


--
-- Name: inventory_leftovers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_leftovers (
    id uuid NOT NULL,
    inventory_item_id uuid NOT NULL,
    leftover_id uuid,
    goods_id uuid NOT NULL,
    package_id uuid NOT NULL,
    quantity integer NOT NULL,
    batch character varying(50) NOT NULL,
    manufacture_date date NOT NULL,
    bb_date date NOT NULL,
    source_type smallint DEFAULT '1'::smallint NOT NULL,
    creator_id uuid NOT NULL,
    approved_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    current_leftovers integer,
    expiration_term integer,
    container_registers_id uuid,
    condition boolean DEFAULT false NOT NULL,
    area character varying(255)
);


--
-- Name: inventory_manual_leftover_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_manual_leftover_logs (
    id bigint NOT NULL,
    leftover_id uuid NOT NULL,
    quantity_before numeric(18,3),
    quantity_after numeric(18,3),
    area character varying(64),
    executor_id uuid,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    group_id uuid,
    group_type character varying(64)
);


--
-- Name: inventory_manual_leftover_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_manual_leftover_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_manual_leftover_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_manual_leftover_logs_id_seq OWNED BY public.inventory_manual_leftover_logs.id;


--
-- Name: inventory_performers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_performers (
    inventory_id uuid NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_documents (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    invoice_id uuid NOT NULL,
    document_id uuid NOT NULL
);


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoices (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    company_provider_id uuid NOT NULL,
    company_customer_id uuid NOT NULL,
    responsible_supply_id uuid NOT NULL,
    responsible_receive_id uuid NOT NULL,
    contract_id uuid NOT NULL,
    invoice_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    sum double precision NOT NULL,
    sum_with_pdv double precision NOT NULL,
    payment_term timestamp(0) without time zone NOT NULL,
    status_id smallint NOT NULL,
    creator_company_id uuid NOT NULL
);


--
-- Name: leftovers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.leftovers (
    id uuid NOT NULL,
    local_id bigint NOT NULL,
    goods_id uuid NOT NULL,
    quantity integer NOT NULL,
    batch character varying(50) NOT NULL,
    manufacture_date date NOT NULL,
    bb_date date NOT NULL,
    package_id uuid NOT NULL,
    creator_company_id uuid NOT NULL,
    warehouse_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    has_condition boolean DEFAULT true NOT NULL,
    container_id uuid,
    expiration_term integer DEFAULT 30 NOT NULL,
    cell_id uuid,
    status_id character varying(255) DEFAULT '1'::character varying NOT NULL,
    parent_id uuid,
    is_reserved boolean DEFAULT false NOT NULL,
    document_id uuid,
    CONSTRAINT leftovers_status_id_check CHECK (((status_id)::text = ANY (ARRAY[('1'::character varying)::text, ('2'::character varying)::text, ('3'::character varying)::text])))
);


--
-- Name: leftovers_erp; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.leftovers_erp (
    id uuid NOT NULL,
    warehouse_erp_id character varying(100) NOT NULL,
    goods_erp_id character varying(100) NOT NULL,
    batch character varying(50),
    quantity numeric(15,3) DEFAULT '0'::numeric NOT NULL,
    creator_company_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    local_id bigint DEFAULT '1'::bigint NOT NULL
);


--
-- Name: leftovers_local_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

ALTER TABLE public.leftovers ALTER COLUMN local_id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.leftovers_local_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: legal_companies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.legal_companies (
    id uuid NOT NULL,
    name character varying(50) NOT NULL,
    edrpou bigint,
    legal_type_id bigint,
    legal_address_id bigint,
    install_doctype character varying(5),
    reg_doctype character varying(5),
    deleted_at timestamp(0) without time zone,
    three_pl smallint,
    reg_docname character varying(255),
    install_docname character varying(255)
);


--
-- Name: legal_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.legal_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: legal_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.legal_types_id_seq OWNED BY public._d_legal_types.id;


--
-- Name: locations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.locations (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(100) NOT NULL,
    company_id uuid NOT NULL,
    country_id bigint,
    settlement_id bigint,
    street_info json,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    url text
);


--
-- Name: login_histories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.login_histories (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: login_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.login_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: login_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.login_histories_id_seq OWNED BY public.login_histories.id;


--
-- Name: measurement_units_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.measurement_units_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: measurement_units_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.measurement_units_id_seq OWNED BY public._d_measurement_units.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- Name: obligation_adjustments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.obligation_adjustments (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    invoice_obligation_id uuid NOT NULL,
    type_id smallint NOT NULL,
    failure_id bigint NOT NULL,
    status_id bigint NOT NULL,
    address_id bigint NOT NULL,
    sum double precision NOT NULL,
    comment text
);


--
-- Name: operational_costs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operational_costs (
    id bigint NOT NULL,
    company_id uuid,
    distance double precision NOT NULL,
    additional_points_number integer NOT NULL,
    trip_days smallint DEFAULT '0'::smallint NOT NULL,
    trip_time time(0) without time zone DEFAULT '00:00:00'::time without time zone NOT NULL,
    price integer NOT NULL,
    currency smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: operational_costs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.operational_costs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: operational_costs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.operational_costs_id_seq OWNED BY public.operational_costs.id;


--
-- Name: outcome_document_leftovers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.outcome_document_leftovers (
    id uuid NOT NULL,
    quantity integer NOT NULL,
    document_id uuid NOT NULL,
    leftover_id uuid NOT NULL,
    creator_id uuid NOT NULL,
    processing_type character varying(20) NOT NULL,
    processing_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    package_id uuid NOT NULL
);


--
-- Name: package_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.package_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: package_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.package_types_id_seq OWNED BY public._d_package_types.id;


--
-- Name: packages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.packages (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    parent_id uuid,
    type_id bigint NOT NULL,
    name character varying(50) NOT NULL,
    main_units_number double precision NOT NULL,
    package_count integer,
    weight_netto double precision NOT NULL,
    weight_brutto double precision NOT NULL,
    height double precision NOT NULL,
    width double precision NOT NULL,
    length double precision NOT NULL,
    goods_id uuid NOT NULL,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    child_number smallint DEFAULT '1'::smallint NOT NULL,
    child_id uuid,
    erp_id character varying(255)
);


--
-- Name: pallet_registers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pallet_registers (
    id bigint NOT NULL,
    code integer NOT NULL,
    supply_code integer NOT NULL,
    pallet_id uuid NOT NULL,
    is_active boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pallet_registers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pallet_registers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pallet_registers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pallet_registers_id_seq OWNED BY public.pallet_registers.id;


--
-- Name: pallets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pallets (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    product character varying(20) NOT NULL,
    warehouse_id uuid NOT NULL,
    consigment integer NOT NULL,
    amount integer NOT NULL,
    series integer NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cell_id uuid
);


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: physical_companies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.physical_companies (
    id uuid NOT NULL,
    first_name character varying(50) NOT NULL,
    surname character varying(50) NOT NULL,
    patronymic character varying(255),
    deleted_at timestamp(0) without time zone
);


--
-- Name: positions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.positions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: positions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.positions_id_seq OWNED BY public._d_positions.id;


--
-- Name: regions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.regions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: regions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.regions_id_seq OWNED BY public._d_regions.id;


--
-- Name: register_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.register_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: register_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.register_statuses_id_seq OWNED BY public._d_register_statuses.id;


--
-- Name: registers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.registers (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    time_arrival time(0) without time zone NOT NULL,
    auto_name character varying(50) NOT NULL,
    licence_plate character varying(255),
    mono_pallet smallint DEFAULT '0'::smallint NOT NULL,
    collect_pallet smallint DEFAULT '0'::smallint NOT NULL,
    download_method_id bigint,
    download_zone_id bigint,
    storekeeper_id uuid,
    manager_id uuid,
    status_id bigint,
    register timestamp(0) without time zone,
    entrance timestamp(0) without time zone,
    departure timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    warehouse_id uuid NOT NULL,
    transport_planning_id uuid,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: regulations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.regulations (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(255) NOT NULL,
    type smallint NOT NULL,
    service_side smallint NOT NULL,
    _lft integer NOT NULL,
    _rgt integer NOT NULL,
    settings json NOT NULL,
    draft boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    parent_id uuid,
    creator_company_id uuid NOT NULL
);


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: row_cell_info; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.row_cell_info (
    id uuid NOT NULL,
    height double precision,
    width double precision,
    deep double precision,
    max_weight double precision,
    rack integer DEFAULT 1 NOT NULL,
    floor integer DEFAULT 1 NOT NULL,
    "column" integer DEFAULT 1 NOT NULL,
    cell_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone
);


--
-- Name: rows; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rows (
    sector_id uuid NOT NULL,
    floors integer NOT NULL,
    racks integer NOT NULL,
    cell_count integer NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    name character varying(100) NOT NULL,
    save_type smallint DEFAULT '1'::smallint NOT NULL,
    numeration_type smallint DEFAULT '1'::smallint NOT NULL,
    id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    cell_props jsonb
);


--
-- Name: schedule_exceptions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedule_exceptions (
    id bigint NOT NULL,
    date_from date NOT NULL,
    date_to date,
    type_id bigint NOT NULL,
    work_from character varying(5),
    work_to character varying(5),
    break_from character varying(5),
    break_to character varying(5),
    user_id uuid,
    warehouse_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: schedule_exceptions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedule_exceptions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedule_exceptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedule_exceptions_id_seq OWNED BY public.schedule_exceptions.id;


--
-- Name: schedule_patterns; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedule_patterns (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    schedule json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    type character varying(20) NOT NULL,
    creator_company_id uuid
);


--
-- Name: schedule_patterns_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedule_patterns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedule_patterns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedule_patterns_id_seq OWNED BY public.schedule_patterns.id;


--
-- Name: schedules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedules (
    id bigint NOT NULL,
    weekday character varying(10) NOT NULL,
    is_day_off boolean,
    start_at character varying(5),
    end_at character varying(5),
    break_start_at character varying(5),
    break_end_at character varying(5),
    user_id uuid,
    warehouse_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedules_id_seq OWNED BY public.schedules.id;


--
-- Name: sectors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sectors (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    zone_id uuid,
    name character varying(100) NOT NULL,
    color character varying(25),
    has_temp boolean DEFAULT false NOT NULL,
    temp_from double precision,
    temp_to double precision,
    has_humidity boolean DEFAULT false NOT NULL,
    humidity_from double precision,
    humidity_to double precision,
    deleted_at timestamp(0) without time zone,
    code character varying(8)
);


--
-- Name: service_by_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_by_documents (
    id bigint NOT NULL,
    service_id uuid,
    document_id uuid,
    data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_by_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_by_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_by_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_by_documents_id_seq OWNED BY public.service_by_documents.id;


--
-- Name: service_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_categories_id_seq OWNED BY public._d_service_categories.id;


--
-- Name: services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.services (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(255) NOT NULL,
    comment text,
    category_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_draft boolean DEFAULT false NOT NULL,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: settlements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.settlements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: settlements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.settlements_id_seq OWNED BY public._d_settlements.id;


--
-- Name: sku_by_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sku_by_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sku_by_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sku_by_documents_id_seq OWNED BY public.goods_by_documents.id;


--
-- Name: sku_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sku_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sku_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sku_categories_id_seq OWNED BY public._d_goods_categories.id;


--
-- Name: storage_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.storage_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: storage_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.storage_types_id_seq OWNED BY public._d_storage_types.id;


--
-- Name: streets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.streets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: streets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.streets_id_seq OWNED BY public._d_streets.id;


--
-- Name: task_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.task_items (
    id uuid NOT NULL,
    local_id character varying(255),
    task_id uuid,
    leftover_id uuid,
    goods_id uuid,
    data json,
    package character varying(255),
    container_id character varying(255),
    main_unit_quantity numeric(15,3),
    package_quantity numeric(15,3),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: task_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.task_logs (
    id bigint NOT NULL,
    document_id uuid,
    task_id uuid,
    data json NOT NULL,
    creator_id uuid,
    creator_company_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: task_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.task_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: task_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.task_logs_id_seq OWNED BY public.task_logs.id;


--
-- Name: tasks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks (
    id uuid NOT NULL,
    local_id bigint,
    processing_type character varying(50),
    type_id integer NOT NULL,
    kind character varying(50) NOT NULL,
    executors json,
    status character varying(255) NOT NULL,
    document_id uuid,
    priority integer DEFAULT 1 NOT NULL,
    comment text,
    task_data json,
    creator_company_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    cell_id uuid NOT NULL,
    started_at timestamp(0) without time zone,
    finished_at timestamp(0) without time zone,
    formation_type character varying(50)
);


--
-- Name: telescope_entries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_entries (
    sequence bigint NOT NULL,
    uuid uuid NOT NULL,
    batch_id uuid NOT NULL,
    family_hash character varying(255),
    should_display_on_index boolean DEFAULT true NOT NULL,
    type character varying(20) NOT NULL,
    content text NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.telescope_entries_sequence_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.telescope_entries_sequence_seq OWNED BY public.telescope_entries.sequence;


--
-- Name: telescope_entries_tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_entries_tags (
    entry_uuid uuid NOT NULL,
    tag character varying(255) NOT NULL
);


--
-- Name: telescope_monitoring; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_monitoring (
    tag character varying(255) NOT NULL
);


--
-- Name: terminal_leftover_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.terminal_leftover_logs (
    id bigint NOT NULL,
    document_id uuid NOT NULL,
    leftover_id uuid NOT NULL,
    container_id uuid,
    quantity double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    package_id uuid NOT NULL,
    type character varying(20)
);


--
-- Name: terminal_leftover_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.terminal_leftover_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: terminal_leftover_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.terminal_leftover_logs_id_seq OWNED BY public.terminal_leftover_logs.id;


--
-- Name: transport_brands_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_brands_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_brands_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_brands_id_seq OWNED BY public._d_transport_brands.id;


--
-- Name: transport_downloads_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_downloads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_downloads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_downloads_id_seq OWNED BY public._d_transport_downloads.id;


--
-- Name: transport_kinds_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_kinds_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_kinds_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_kinds_id_seq OWNED BY public._d_transport_categories.id;


--
-- Name: transport_models_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_models_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_models_id_seq OWNED BY public._d_transport_models.id;


--
-- Name: transport_planning_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transport_planning_documents (
    id bigint NOT NULL,
    transport_planing_id uuid NOT NULL,
    document_id uuid NOT NULL,
    download_start timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    download_end timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    unloading_start timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    unloading_end timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: transport_planing_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_planing_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_planing_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_planing_documents_id_seq OWNED BY public.transport_planning_documents.id;


--
-- Name: transport_planning_failure_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_planning_failure_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_planning_failure_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_planning_failure_types_id_seq OWNED BY public._d_transport_planning_failure_types.id;


--
-- Name: transport_planning_failures; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transport_planning_failures (
    id bigint NOT NULL,
    cause_failure character varying(255),
    culprit_of_failure character varying(255),
    cost_of_fines character varying(255),
    type_id bigint,
    status_id bigint NOT NULL,
    comment text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: transport_planning_failures_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_planning_failures_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_planning_failures_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_planning_failures_id_seq OWNED BY public.transport_planning_failures.id;


--
-- Name: transport_planning_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_planning_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_planning_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_planning_statuses_id_seq OWNED BY public._d_transport_planning_statuses.id;


--
-- Name: transport_planning_to_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transport_planning_to_statuses (
    id bigint NOT NULL,
    transport_planning_id uuid NOT NULL,
    status_id bigint NOT NULL,
    address_id bigint,
    date timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    comment text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: transport_planning_to_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_planning_to_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_planning_to_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_planning_to_statuses_id_seq OWNED BY public.transport_planning_to_statuses.id;


--
-- Name: transport_plannings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transport_plannings (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    creator_id uuid NOT NULL,
    company_provider_id uuid NOT NULL,
    company_carrier_id uuid,
    transport_id uuid,
    additional_equipment_id uuid,
    payer_id uuid NOT NULL,
    driver_id uuid,
    price double precision DEFAULT '0'::double precision,
    with_pdv boolean DEFAULT false,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    comment text,
    is_reserved boolean DEFAULT false NOT NULL,
    deleted_at timestamp(0) without time zone,
    three_pl boolean DEFAULT false NOT NULL,
    auto_search boolean DEFAULT false NOT NULL,
    init_transport boolean DEFAULT false NOT NULL,
    creator_company_id uuid NOT NULL
);


--
-- Name: transport_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.transport_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transport_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.transport_types_id_seq OWNED BY public._d_transport_types.id;


--
-- Name: transports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.transports (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    brand_id bigint NOT NULL,
    model_id bigint NOT NULL,
    license_plate character varying(8) NOT NULL,
    type_id bigint NOT NULL,
    length double precision,
    width double precision,
    height double precision,
    volume double precision,
    weight double precision,
    capacity_eu double precision,
    capacity_am double precision,
    spending_empty double precision NOT NULL,
    spending_full double precision NOT NULL,
    download_methods json,
    equipment_id uuid,
    adr_id bigint,
    manufacture_year integer NOT NULL,
    company_id uuid NOT NULL,
    driver_id uuid NOT NULL,
    registration_country_id bigint NOT NULL,
    img_type character varying(5),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    carrying_capacity double precision,
    hydroboard boolean,
    category_id bigint NOT NULL,
    deleted_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: user_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_statuses_id_seq OWNED BY public._d_user_statuses.id;


--
-- Name: user_working_data; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_working_data (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    company_id uuid,
    role_id bigint,
    position_id bigint,
    driving_license_number character varying(9),
    health_book_number character varying(20),
    driving_license_doctype character varying(5),
    health_book_doctype character varying(5),
    driver_license_date date,
    health_book_date date,
    user_id uuid,
    workspace_id bigint,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL,
    current_warehouse_id uuid,
    current_warehouse_app_id uuid
);


--
-- Name: user_working_data_warehouse; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_working_data_warehouse (
    user_working_data_id uuid NOT NULL,
    warehouse_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(50),
    surname character varying(50),
    patronymic character varying(50),
    birthday date,
    phone character varying(15),
    email character varying(100),
    password character varying(255) NOT NULL,
    avatar_type character varying(10),
    new_user boolean DEFAULT true NOT NULL,
    last_seen date,
    deleted_at timestamp(0) without time zone,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    current_workspace_id bigint,
    sex boolean,
    pin_hash character varying(255),
    pin_attempts smallint DEFAULT '0'::smallint NOT NULL,
    pin_locked_until timestamp(0) without time zone,
    pin character varying(4)
);


--
-- Name: verification_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.verification_codes (
    id bigint NOT NULL,
    login character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: verification_codes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.verification_codes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: verification_codes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.verification_codes_id_seq OWNED BY public.verification_codes.id;


--
-- Name: warehouse_erp_assignments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.warehouse_erp_assignments (
    warehouse_id uuid NOT NULL,
    warehouse_erp_id uuid NOT NULL
);


--
-- Name: warehouse_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.warehouse_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: warehouse_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.warehouse_types_id_seq OWNED BY public._d_warehouse_types.id;


--
-- Name: warehouse_zones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.warehouse_zones (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(100) NOT NULL,
    has_temp boolean NOT NULL,
    temp_from double precision,
    temp_to double precision,
    has_humidity boolean NOT NULL,
    humidity_from double precision,
    humidity_to double precision,
    warehouse_id uuid NOT NULL,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    color character varying(25),
    zone_type bigint,
    zone_subtype bigint
);


--
-- Name: warehouses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.warehouses (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    type_id bigint,
    user_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    name character varying(30) NOT NULL,
    creator_company_id uuid NOT NULL,
    location_id uuid,
    warehouse_erp_id uuid
);


--
-- Name: warehouses_erp; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.warehouses_erp (
    id uuid NOT NULL,
    local_id bigint DEFAULT '1'::bigint NOT NULL,
    name character varying(100) NOT NULL,
    id_erp character varying(100) NOT NULL,
    creator_company_id uuid NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: websockets_statistics_entries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.websockets_statistics_entries (
    id integer NOT NULL,
    app_id character varying(255) NOT NULL,
    peak_connection_count integer NOT NULL,
    websocket_message_count integer NOT NULL,
    api_message_count integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: websockets_statistics_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.websockets_statistics_entries_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: websockets_statistics_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.websockets_statistics_entries_id_seq OWNED BY public.websockets_statistics_entries.id;


--
-- Name: workspaces; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.workspaces (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    user_id uuid NOT NULL,
    avatar_type character varying(5),
    avatar_color character varying(7),
    warehouse json,
    employees json,
    integration json,
    employees_count smallint,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    creator_company_id uuid NOT NULL
);


--
-- Name: workspaces_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.workspaces_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: workspaces_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.workspaces_id_seq OWNED BY public.workspaces.id;


--
-- Name: _d_additional_equipment_brands id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_brands ALTER COLUMN id SET DEFAULT nextval('public.additional_equipment_brands_id_seq'::regclass);


--
-- Name: _d_additional_equipment_models id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_models ALTER COLUMN id SET DEFAULT nextval('public.additional_equipment_models_id_seq'::regclass);


--
-- Name: _d_additional_equipment_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_types ALTER COLUMN id SET DEFAULT nextval('public.additional_equipment_types_id_seq'::regclass);


--
-- Name: _d_adrs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_adrs ALTER COLUMN id SET DEFAULT nextval('public.adrs_id_seq'::regclass);


--
-- Name: _d_cargo_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_cargo_types ALTER COLUMN id SET DEFAULT nextval('public.cargo_types_id_seq'::regclass);


--
-- Name: _d_cell_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_cell_statuses ALTER COLUMN id SET DEFAULT nextval('public.cell_statuses_id_seq'::regclass);


--
-- Name: _d_company_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_categories ALTER COLUMN id SET DEFAULT nextval('public.company_categories_id_seq'::regclass);


--
-- Name: _d_company_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_statuses ALTER COLUMN id SET DEFAULT nextval('public.company_statuses_id_seq'::regclass);


--
-- Name: _d_company_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_types ALTER COLUMN id SET DEFAULT nextval('public.company_types_id_seq'::regclass);


--
-- Name: _d_container_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_container_types ALTER COLUMN id SET DEFAULT nextval('public.container_types_id_seq'::regclass);


--
-- Name: _d_countries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_countries ALTER COLUMN id SET DEFAULT nextval('public.countries_id_seq'::regclass);


--
-- Name: _d_delivery_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_delivery_types ALTER COLUMN id SET DEFAULT nextval('public.delivery_types_id_seq'::regclass);


--
-- Name: _d_doctype_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_doctype_statuses ALTER COLUMN id SET DEFAULT nextval('public.doctype_statuses_id_seq'::regclass);


--
-- Name: _d_document_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_document_statuses ALTER COLUMN id SET DEFAULT nextval('public.document_statuses_id_seq'::regclass);


--
-- Name: _d_download_zones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_download_zones ALTER COLUMN id SET DEFAULT nextval('public.download_zones_id_seq'::regclass);


--
-- Name: _d_exception_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_exception_types ALTER COLUMN id SET DEFAULT nextval('public.exception_types_id_seq'::regclass);


--
-- Name: _d_goods_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_goods_categories ALTER COLUMN id SET DEFAULT nextval('public.sku_categories_id_seq'::regclass);


--
-- Name: _d_legal_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_legal_types ALTER COLUMN id SET DEFAULT nextval('public.legal_types_id_seq'::regclass);


--
-- Name: _d_measurement_units id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_measurement_units ALTER COLUMN id SET DEFAULT nextval('public.measurement_units_id_seq'::regclass);


--
-- Name: _d_package_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_package_types ALTER COLUMN id SET DEFAULT nextval('public.package_types_id_seq'::regclass);


--
-- Name: _d_positions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_positions ALTER COLUMN id SET DEFAULT nextval('public.positions_id_seq'::regclass);


--
-- Name: _d_regions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_regions ALTER COLUMN id SET DEFAULT nextval('public.regions_id_seq'::regclass);


--
-- Name: _d_register_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_register_statuses ALTER COLUMN id SET DEFAULT nextval('public.register_statuses_id_seq'::regclass);


--
-- Name: _d_roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_roles ALTER COLUMN id SET DEFAULT nextval('public._d_roles_id_seq'::regclass);


--
-- Name: _d_service_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_service_categories ALTER COLUMN id SET DEFAULT nextval('public.service_categories_id_seq'::regclass);


--
-- Name: _d_settlements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_settlements ALTER COLUMN id SET DEFAULT nextval('public.settlements_id_seq'::regclass);


--
-- Name: _d_storage_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_storage_types ALTER COLUMN id SET DEFAULT nextval('public.storage_types_id_seq'::regclass);


--
-- Name: _d_streets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_streets ALTER COLUMN id SET DEFAULT nextval('public.streets_id_seq'::regclass);


--
-- Name: _d_task_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_task_types ALTER COLUMN id SET DEFAULT nextval('public._d_task_types_id_seq'::regclass);


--
-- Name: _d_transport_brands id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_brands ALTER COLUMN id SET DEFAULT nextval('public.transport_brands_id_seq'::regclass);


--
-- Name: _d_transport_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_categories ALTER COLUMN id SET DEFAULT nextval('public.transport_kinds_id_seq'::regclass);


--
-- Name: _d_transport_downloads id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_downloads ALTER COLUMN id SET DEFAULT nextval('public.transport_downloads_id_seq'::regclass);


--
-- Name: _d_transport_models id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_models ALTER COLUMN id SET DEFAULT nextval('public.transport_models_id_seq'::regclass);


--
-- Name: _d_transport_planning_failure_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_planning_failure_types ALTER COLUMN id SET DEFAULT nextval('public.transport_planning_failure_types_id_seq'::regclass);


--
-- Name: _d_transport_planning_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_planning_statuses ALTER COLUMN id SET DEFAULT nextval('public.transport_planning_statuses_id_seq'::regclass);


--
-- Name: _d_transport_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_types ALTER COLUMN id SET DEFAULT nextval('public.transport_types_id_seq'::regclass);


--
-- Name: _d_user_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_user_statuses ALTER COLUMN id SET DEFAULT nextval('public.user_statuses_id_seq'::regclass);


--
-- Name: _d_warehouse_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_warehouse_types ALTER COLUMN id SET DEFAULT nextval('public.warehouse_types_id_seq'::regclass);


--
-- Name: _d_zone_subtypes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_subtypes ALTER COLUMN id SET DEFAULT nextval('public._d_zone_subtypes_id_seq'::regclass);


--
-- Name: _d_zone_type_subtype id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_type_subtype ALTER COLUMN id SET DEFAULT nextval('public._d_zone_type_subtype_id_seq'::regclass);


--
-- Name: _d_zone_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_types ALTER COLUMN id SET DEFAULT nextval('public._d_zone_types_id_seq'::regclass);


--
-- Name: address_details id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address_details ALTER COLUMN id SET DEFAULT nextval('public.address_details_id_seq'::regclass);


--
-- Name: bookmarks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookmarks ALTER COLUMN id SET DEFAULT nextval('public.bookmarks_id_seq'::regclass);


--
-- Name: company_to_workspaces id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_to_workspaces ALTER COLUMN id SET DEFAULT nextval('public.company_to_workspaces_id_seq'::regclass);


--
-- Name: container_by_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_by_documents ALTER COLUMN id SET DEFAULT nextval('public.container_by_documents_id_seq'::regclass);


--
-- Name: contracts_comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts_comments ALTER COLUMN id SET DEFAULT nextval('public.contracts_comments_id_seq'::regclass);


--
-- Name: doctype_structure id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctype_structure ALTER COLUMN id SET DEFAULT nextval('public.doctype_structure_id_seq'::regclass);


--
-- Name: document_leftover_reservations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_leftover_reservations ALTER COLUMN id SET DEFAULT nextval('public.document_leftover_reservations_id_seq'::regclass);


--
-- Name: document_relations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_relations ALTER COLUMN id SET DEFAULT nextval('public.document_relations_id_seq'::regclass);


--
-- Name: entity_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entity_logs ALTER COLUMN id SET DEFAULT nextval('public.entity_logs_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: goods_by_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_by_documents ALTER COLUMN id SET DEFAULT nextval('public.sku_by_documents_id_seq'::regclass);


--
-- Name: integrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.integrations ALTER COLUMN id SET DEFAULT nextval('public.integrations_id_seq'::regclass);


--
-- Name: inventories local_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories ALTER COLUMN local_id SET DEFAULT nextval('public.inventories_local_id_seq'::regclass);


--
-- Name: inventory_goods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_goods ALTER COLUMN id SET DEFAULT nextval('public.inventory_goods_id_seq'::regclass);


--
-- Name: inventory_manual_leftover_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_manual_leftover_logs ALTER COLUMN id SET DEFAULT nextval('public.inventory_manual_leftover_logs_id_seq'::regclass);


--
-- Name: login_histories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_histories ALTER COLUMN id SET DEFAULT nextval('public.login_histories_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: operational_costs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operational_costs ALTER COLUMN id SET DEFAULT nextval('public.operational_costs_id_seq'::regclass);


--
-- Name: pallet_registers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallet_registers ALTER COLUMN id SET DEFAULT nextval('public.pallet_registers_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: schedule_exceptions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_exceptions ALTER COLUMN id SET DEFAULT nextval('public.schedule_exceptions_id_seq'::regclass);


--
-- Name: schedule_patterns id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_patterns ALTER COLUMN id SET DEFAULT nextval('public.schedule_patterns_id_seq'::regclass);


--
-- Name: schedules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules ALTER COLUMN id SET DEFAULT nextval('public.schedules_id_seq'::regclass);


--
-- Name: service_by_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_by_documents ALTER COLUMN id SET DEFAULT nextval('public.service_by_documents_id_seq'::regclass);


--
-- Name: task_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_logs ALTER COLUMN id SET DEFAULT nextval('public.task_logs_id_seq'::regclass);


--
-- Name: telescope_entries sequence; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries ALTER COLUMN sequence SET DEFAULT nextval('public.telescope_entries_sequence_seq'::regclass);


--
-- Name: terminal_leftover_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs ALTER COLUMN id SET DEFAULT nextval('public.terminal_leftover_logs_id_seq'::regclass);


--
-- Name: transport_planning_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_documents ALTER COLUMN id SET DEFAULT nextval('public.transport_planing_documents_id_seq'::regclass);


--
-- Name: transport_planning_failures id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_failures ALTER COLUMN id SET DEFAULT nextval('public.transport_planning_failures_id_seq'::regclass);


--
-- Name: transport_planning_to_statuses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_to_statuses ALTER COLUMN id SET DEFAULT nextval('public.transport_planning_to_statuses_id_seq'::regclass);


--
-- Name: verification_codes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_codes ALTER COLUMN id SET DEFAULT nextval('public.verification_codes_id_seq'::regclass);


--
-- Name: websockets_statistics_entries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.websockets_statistics_entries ALTER COLUMN id SET DEFAULT nextval('public.websockets_statistics_entries_id_seq'::regclass);


--
-- Name: workspaces id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.workspaces ALTER COLUMN id SET DEFAULT nextval('public.workspaces_id_seq'::regclass);


--
-- Name: _d_package_types _d_package_types_key_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_package_types
    ADD CONSTRAINT _d_package_types_key_unique UNIQUE (key);


--
-- Name: _d_roles _d_roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_roles
    ADD CONSTRAINT _d_roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: _d_roles _d_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_roles
    ADD CONSTRAINT _d_roles_pkey PRIMARY KEY (id);


--
-- Name: _d_task_types _d_task_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_task_types
    ADD CONSTRAINT _d_task_types_pkey PRIMARY KEY (id);


--
-- Name: _d_zone_subtypes _d_zone_subtypes_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_subtypes
    ADD CONSTRAINT _d_zone_subtypes_name_unique UNIQUE (name);


--
-- Name: _d_zone_subtypes _d_zone_subtypes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_subtypes
    ADD CONSTRAINT _d_zone_subtypes_pkey PRIMARY KEY (id);


--
-- Name: _d_zone_type_subtype _d_zone_type_subtype_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_type_subtype
    ADD CONSTRAINT _d_zone_type_subtype_pkey PRIMARY KEY (id);


--
-- Name: _d_zone_types _d_zone_types_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_types
    ADD CONSTRAINT _d_zone_types_name_unique UNIQUE (name);


--
-- Name: _d_zone_types _d_zone_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_types
    ADD CONSTRAINT _d_zone_types_pkey PRIMARY KEY (id);


--
-- Name: _d_zone_type_subtype _d_ztts_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_type_subtype
    ADD CONSTRAINT _d_ztts_unique UNIQUE (zone_type_id, zone_subtype_id);


--
-- Name: _d_additional_equipment_brands additional_equipment_brands_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_brands
    ADD CONSTRAINT additional_equipment_brands_pkey PRIMARY KEY (id);


--
-- Name: _d_additional_equipment_models additional_equipment_models_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_models
    ADD CONSTRAINT additional_equipment_models_pkey PRIMARY KEY (id);


--
-- Name: additional_equipment additional_equipment_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_pkey PRIMARY KEY (id);


--
-- Name: _d_additional_equipment_types additional_equipment_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_types
    ADD CONSTRAINT additional_equipment_types_pkey PRIMARY KEY (id);


--
-- Name: address_details address_details_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address_details
    ADD CONSTRAINT address_details_pkey PRIMARY KEY (id);


--
-- Name: _d_adrs adrs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_adrs
    ADD CONSTRAINT adrs_pkey PRIMARY KEY (id);


--
-- Name: barcodes barcodes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.barcodes
    ADD CONSTRAINT barcodes_pkey PRIMARY KEY (id);


--
-- Name: bookmarks bookmarks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookmarks
    ADD CONSTRAINT bookmarks_pkey PRIMARY KEY (id);


--
-- Name: _d_cargo_types cargo_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_cargo_types
    ADD CONSTRAINT cargo_types_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: _d_cell_statuses cell_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_cell_statuses
    ADD CONSTRAINT cell_statuses_pkey PRIMARY KEY (id);


--
-- Name: cells cells_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cells
    ADD CONSTRAINT cells_pkey PRIMARY KEY (id);


--
-- Name: companies companies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_pkey PRIMARY KEY (id);


--
-- Name: _d_company_categories company_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_categories
    ADD CONSTRAINT company_categories_pkey PRIMARY KEY (id);


--
-- Name: company_requests company_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_requests
    ADD CONSTRAINT company_requests_pkey PRIMARY KEY (id);


--
-- Name: _d_company_statuses company_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_statuses
    ADD CONSTRAINT company_statuses_pkey PRIMARY KEY (id);


--
-- Name: company_to_workspaces company_to_workspaces_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_to_workspaces
    ADD CONSTRAINT company_to_workspaces_pkey PRIMARY KEY (id);


--
-- Name: _d_company_types company_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_types
    ADD CONSTRAINT company_types_pkey PRIMARY KEY (id);


--
-- Name: container_by_documents container_by_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_by_documents
    ADD CONSTRAINT container_by_documents_pkey PRIMARY KEY (id);


--
-- Name: container_registers container_registers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_registers
    ADD CONSTRAINT container_registers_pkey PRIMARY KEY (id);


--
-- Name: _d_container_types container_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_container_types
    ADD CONSTRAINT container_types_pkey PRIMARY KEY (id);


--
-- Name: containers containers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.containers
    ADD CONSTRAINT containers_pkey PRIMARY KEY (id);


--
-- Name: contracts_comments contracts_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts_comments
    ADD CONSTRAINT contracts_comments_pkey PRIMARY KEY (id);


--
-- Name: contracts contracts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_pkey PRIMARY KEY (id);


--
-- Name: _d_countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: _d_delivery_types delivery_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_delivery_types
    ADD CONSTRAINT delivery_types_pkey PRIMARY KEY (id);


--
-- Name: doctype_fields doctype_fields_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctype_fields
    ADD CONSTRAINT doctype_fields_pkey PRIMARY KEY (id);


--
-- Name: _d_doctype_statuses doctype_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_doctype_statuses
    ADD CONSTRAINT doctype_statuses_pkey PRIMARY KEY (id);


--
-- Name: doctype_structure doctype_structure_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctype_structure
    ADD CONSTRAINT doctype_structure_pkey PRIMARY KEY (id);


--
-- Name: document_leftover_reservations document_leftover_reservations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_leftover_reservations
    ADD CONSTRAINT document_leftover_reservations_pkey PRIMARY KEY (id);


--
-- Name: document_relations document_relations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_relations
    ADD CONSTRAINT document_relations_pkey PRIMARY KEY (id);


--
-- Name: _d_document_statuses document_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_document_statuses
    ADD CONSTRAINT document_statuses_pkey PRIMARY KEY (id);


--
-- Name: document_types document_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_types
    ADD CONSTRAINT document_types_pkey PRIMARY KEY (id);


--
-- Name: documents documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- Name: _d_download_zones download_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_download_zones
    ADD CONSTRAINT download_zones_pkey PRIMARY KEY (id);


--
-- Name: entity_logs entity_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entity_logs
    ADD CONSTRAINT entity_logs_pkey PRIMARY KEY (id);


--
-- Name: _d_exception_types exception_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_exception_types
    ADD CONSTRAINT exception_types_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: file_loads file_loads_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.file_loads
    ADD CONSTRAINT file_loads_pkey PRIMARY KEY (id);


--
-- Name: goods_kit_items goods_kit_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_kit_items
    ADD CONSTRAINT goods_kit_items_pkey PRIMARY KEY (id);


--
-- Name: goods goods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_pkey PRIMARY KEY (id);


--
-- Name: goods_to_container_registers goods_to_container_registers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_to_container_registers
    ADD CONSTRAINT goods_to_container_registers_pkey PRIMARY KEY (id);


--
-- Name: income_document_leftovers income_document_leftovers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_pkey PRIMARY KEY (id);


--
-- Name: integrations integrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.integrations
    ADD CONSTRAINT integrations_pkey PRIMARY KEY (id);


--
-- Name: inventories inventories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_pkey PRIMARY KEY (id);


--
-- Name: inventory_goods inventory_goods_inventory_id_goods_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_goods
    ADD CONSTRAINT inventory_goods_inventory_id_goods_id_unique UNIQUE (inventory_id, goods_id);


--
-- Name: inventory_goods inventory_goods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_goods
    ADD CONSTRAINT inventory_goods_pkey PRIMARY KEY (id);


--
-- Name: inventory_items inventory_items_inventory_id_cell_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_inventory_id_cell_id_unique UNIQUE (inventory_id, cell_id);


--
-- Name: inventory_items inventory_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_leftovers inventory_leftovers_item_leftover_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_item_leftover_unique UNIQUE (inventory_item_id, leftover_id);


--
-- Name: inventory_leftovers inventory_leftovers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_pkey PRIMARY KEY (id);


--
-- Name: inventory_manual_leftover_logs inventory_manual_leftover_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_manual_leftover_logs
    ADD CONSTRAINT inventory_manual_leftover_logs_pkey PRIMARY KEY (id);


--
-- Name: inventory_performers inventory_performers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_performers
    ADD CONSTRAINT inventory_performers_pkey PRIMARY KEY (inventory_id, user_id);


--
-- Name: invoice_documents invoice_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_documents
    ADD CONSTRAINT invoice_documents_pkey PRIMARY KEY (id);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: leftovers_erp leftovers_erp_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers_erp
    ADD CONSTRAINT leftovers_erp_pkey PRIMARY KEY (id);


--
-- Name: leftovers leftovers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_pkey PRIMARY KEY (id);


--
-- Name: legal_companies legal_companies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.legal_companies
    ADD CONSTRAINT legal_companies_pkey PRIMARY KEY (id);


--
-- Name: _d_legal_types legal_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_legal_types
    ADD CONSTRAINT legal_types_pkey PRIMARY KEY (id);


--
-- Name: locations locations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_pkey PRIMARY KEY (id);


--
-- Name: login_histories login_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_histories
    ADD CONSTRAINT login_histories_pkey PRIMARY KEY (id);


--
-- Name: _d_measurement_units measurement_units_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_measurement_units
    ADD CONSTRAINT measurement_units_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: obligation_adjustments obligation_adjustments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.obligation_adjustments
    ADD CONSTRAINT obligation_adjustments_pkey PRIMARY KEY (id);


--
-- Name: operational_costs operational_costs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operational_costs
    ADD CONSTRAINT operational_costs_pkey PRIMARY KEY (id);


--
-- Name: outcome_document_leftovers outcome_document_leftovers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.outcome_document_leftovers
    ADD CONSTRAINT outcome_document_leftovers_pkey PRIMARY KEY (id);


--
-- Name: _d_package_types package_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_package_types
    ADD CONSTRAINT package_types_pkey PRIMARY KEY (id);


--
-- Name: packages packages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_pkey PRIMARY KEY (id);


--
-- Name: pallet_registers pallet_registers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallet_registers
    ADD CONSTRAINT pallet_registers_pkey PRIMARY KEY (id);


--
-- Name: pallets pallets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallets
    ADD CONSTRAINT pallets_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: physical_companies physical_companies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.physical_companies
    ADD CONSTRAINT physical_companies_pkey PRIMARY KEY (id);


--
-- Name: _d_positions positions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_positions
    ADD CONSTRAINT positions_pkey PRIMARY KEY (id);


--
-- Name: _d_regions regions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_regions
    ADD CONSTRAINT regions_pkey PRIMARY KEY (id);


--
-- Name: _d_register_statuses register_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_register_statuses
    ADD CONSTRAINT register_statuses_pkey PRIMARY KEY (id);


--
-- Name: registers registers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_pkey PRIMARY KEY (id);


--
-- Name: regulations regulations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.regulations
    ADD CONSTRAINT regulations_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: row_cell_info row_cell_info_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.row_cell_info
    ADD CONSTRAINT row_cell_info_pkey PRIMARY KEY (id);


--
-- Name: rows rows_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rows
    ADD CONSTRAINT rows_pkey PRIMARY KEY (id);


--
-- Name: schedule_exceptions schedule_exceptions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_exceptions
    ADD CONSTRAINT schedule_exceptions_pkey PRIMARY KEY (id);


--
-- Name: schedule_patterns schedule_patterns_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_patterns
    ADD CONSTRAINT schedule_patterns_pkey PRIMARY KEY (id);


--
-- Name: schedules schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_pkey PRIMARY KEY (id);


--
-- Name: sectors sectors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sectors
    ADD CONSTRAINT sectors_pkey PRIMARY KEY (id);


--
-- Name: service_by_documents service_by_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_by_documents
    ADD CONSTRAINT service_by_documents_pkey PRIMARY KEY (id);


--
-- Name: _d_service_categories service_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_service_categories
    ADD CONSTRAINT service_categories_pkey PRIMARY KEY (id);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: _d_settlements settlements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_settlements
    ADD CONSTRAINT settlements_pkey PRIMARY KEY (id);


--
-- Name: goods_by_documents sku_by_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_by_documents
    ADD CONSTRAINT sku_by_documents_pkey PRIMARY KEY (id);


--
-- Name: _d_goods_categories sku_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_goods_categories
    ADD CONSTRAINT sku_categories_pkey PRIMARY KEY (id);


--
-- Name: _d_storage_types storage_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_storage_types
    ADD CONSTRAINT storage_types_pkey PRIMARY KEY (id);


--
-- Name: _d_streets streets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_streets
    ADD CONSTRAINT streets_pkey PRIMARY KEY (id);


--
-- Name: task_items task_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_items
    ADD CONSTRAINT task_items_pkey PRIMARY KEY (id);


--
-- Name: task_logs task_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_logs
    ADD CONSTRAINT task_logs_pkey PRIMARY KEY (id);


--
-- Name: tasks tasks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);


--
-- Name: telescope_entries telescope_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_pkey PRIMARY KEY (sequence);


--
-- Name: telescope_entries_tags telescope_entries_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries_tags
    ADD CONSTRAINT telescope_entries_tags_pkey PRIMARY KEY (entry_uuid, tag);


--
-- Name: telescope_entries telescope_entries_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_uuid_unique UNIQUE (uuid);


--
-- Name: telescope_monitoring telescope_monitoring_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_monitoring
    ADD CONSTRAINT telescope_monitoring_pkey PRIMARY KEY (tag);


--
-- Name: terminal_leftover_logs terminal_leftover_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs
    ADD CONSTRAINT terminal_leftover_logs_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_brands transport_brands_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_brands
    ADD CONSTRAINT transport_brands_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_downloads transport_downloads_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_downloads
    ADD CONSTRAINT transport_downloads_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_categories transport_kinds_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_categories
    ADD CONSTRAINT transport_kinds_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_models transport_models_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_models
    ADD CONSTRAINT transport_models_pkey PRIMARY KEY (id);


--
-- Name: transport_planning_documents transport_planing_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_documents
    ADD CONSTRAINT transport_planing_documents_pkey PRIMARY KEY (id);


--
-- Name: transport_plannings transport_planing_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_planning_failure_types transport_planning_failure_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_planning_failure_types
    ADD CONSTRAINT transport_planning_failure_types_pkey PRIMARY KEY (id);


--
-- Name: transport_planning_failures transport_planning_failures_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_failures
    ADD CONSTRAINT transport_planning_failures_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_planning_statuses transport_planning_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_planning_statuses
    ADD CONSTRAINT transport_planning_statuses_pkey PRIMARY KEY (id);


--
-- Name: transport_planning_to_statuses transport_planning_to_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_to_statuses
    ADD CONSTRAINT transport_planning_to_statuses_pkey PRIMARY KEY (id);


--
-- Name: _d_transport_types transport_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_types
    ADD CONSTRAINT transport_types_pkey PRIMARY KEY (id);


--
-- Name: transports transports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_pkey PRIMARY KEY (id);


--
-- Name: leftovers_erp unique_leftover_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers_erp
    ADD CONSTRAINT unique_leftover_key UNIQUE (goods_erp_id, batch, warehouse_erp_id);


--
-- Name: _d_user_statuses user_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_user_statuses
    ADD CONSTRAINT user_statuses_pkey PRIMARY KEY (id);


--
-- Name: user_working_data user_working_data_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_pkey PRIMARY KEY (id);


--
-- Name: user_working_data_warehouse user_working_data_warehouse_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data_warehouse
    ADD CONSTRAINT user_working_data_warehouse_pkey PRIMARY KEY (user_working_data_id, warehouse_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_phone_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_phone_unique UNIQUE (phone);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: verification_codes verification_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_codes
    ADD CONSTRAINT verification_codes_pkey PRIMARY KEY (id);


--
-- Name: warehouse_erp_assignments warehouse_erp_assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_erp_assignments
    ADD CONSTRAINT warehouse_erp_assignments_pkey PRIMARY KEY (warehouse_id, warehouse_erp_id);


--
-- Name: _d_warehouse_types warehouse_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_warehouse_types
    ADD CONSTRAINT warehouse_types_pkey PRIMARY KEY (id);


--
-- Name: warehouse_zones warehouse_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_zones
    ADD CONSTRAINT warehouse_zones_pkey PRIMARY KEY (id);


--
-- Name: warehouses_erp warehouses_erp_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses_erp
    ADD CONSTRAINT warehouses_erp_pkey PRIMARY KEY (id);


--
-- Name: warehouses warehouses_id_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_id_primary PRIMARY KEY (id);


--
-- Name: websockets_statistics_entries websockets_statistics_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.websockets_statistics_entries
    ADD CONSTRAINT websockets_statistics_entries_pkey PRIMARY KEY (id);


--
-- Name: workspaces workspaces_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.workspaces
    ADD CONSTRAINT workspaces_pkey PRIMARY KEY (id);


--
-- Name: _d_roles_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX _d_roles_creator_company_id_index ON public._d_roles USING btree (creator_company_id);


--
-- Name: additional_equipment_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX additional_equipment_creator_company_id_index ON public.additional_equipment USING btree (creator_company_id);


--
-- Name: additional_equipment_models_brand_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX additional_equipment_models_brand_id_index ON public._d_additional_equipment_models USING btree (brand_id);


--
-- Name: additional_equipment_transport_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX additional_equipment_transport_id_index ON public.additional_equipment USING btree (transport_id);


--
-- Name: barcodes_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX barcodes_creator_company_id_index ON public.barcodes USING btree (creator_company_id);


--
-- Name: barcodes_entity_type_entity_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX barcodes_entity_type_entity_id_index ON public.barcodes USING btree (entity_type, entity_id);


--
-- Name: categories_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX categories_erp_id_index ON public.categories USING btree (erp_id);


--
-- Name: categories_parent_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX categories_parent_id_index ON public.categories USING btree (parent_id);


--
-- Name: cells_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cells_status_id_index ON public.cells USING btree (status_id);


--
-- Name: companies_category_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX companies_category_id_index ON public.companies USING btree (category_id);


--
-- Name: companies_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX companies_creator_company_id_index ON public.companies USING btree (creator_company_id);


--
-- Name: companies_creator_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX companies_creator_id_index ON public.companies USING btree (creator_id);


--
-- Name: companies_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX companies_erp_id_index ON public.companies USING btree (erp_id);


--
-- Name: company_categories_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX company_categories_creator_company_id_index ON public._d_company_categories USING btree (creator_company_id);


--
-- Name: company_requests_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX company_requests_company_id_index ON public.company_requests USING btree (company_id);


--
-- Name: company_requests_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX company_requests_user_id_index ON public.company_requests USING btree (user_id);


--
-- Name: company_to_workspaces_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX company_to_workspaces_company_id_index ON public.company_to_workspaces USING btree (company_id);


--
-- Name: company_to_workspaces_workspace_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX company_to_workspaces_workspace_id_index ON public.company_to_workspaces USING btree (workspace_id);


--
-- Name: container_by_documents_container_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX container_by_documents_container_id_index ON public.container_by_documents USING btree (container_id);


--
-- Name: container_by_documents_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX container_by_documents_document_id_index ON public.container_by_documents USING btree (document_id);


--
-- Name: container_registers_container_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX container_registers_container_id_index ON public.container_registers USING btree (container_id);


--
-- Name: container_registers_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX container_registers_creator_company_id_index ON public.container_registers USING btree (creator_company_id);


--
-- Name: containers_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX containers_creator_company_id_index ON public.containers USING btree (creator_company_id);


--
-- Name: containers_type_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX containers_type_id_index ON public.containers USING btree (type_id);


--
-- Name: contracts_comments_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_comments_company_id_index ON public.contracts_comments USING btree (company_id);


--
-- Name: contracts_comments_contract_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_comments_contract_id_index ON public.contracts_comments USING btree (contract_id);


--
-- Name: contracts_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_company_id_index ON public.contracts USING btree (company_id);


--
-- Name: contracts_company_regulation_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_company_regulation_id_index ON public.contracts USING btree (company_regulation_id);


--
-- Name: contracts_counterparty_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_counterparty_id_index ON public.contracts USING btree (counterparty_id);


--
-- Name: contracts_counterparty_regulation_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_counterparty_regulation_id_index ON public.contracts USING btree (counterparty_regulation_id);


--
-- Name: contracts_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX contracts_creator_company_id_index ON public.contracts USING btree (creator_company_id);


--
-- Name: doctype_fields_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctype_fields_creator_company_id_index ON public.doctype_fields USING btree (creator_company_id);


--
-- Name: document_relations_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX document_relations_document_id_index ON public.document_relations USING btree (document_id);


--
-- Name: document_relations_related_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX document_relations_related_id_index ON public.document_relations USING btree (related_id);


--
-- Name: document_types_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX document_types_creator_company_id_index ON public.document_types USING btree (creator_company_id);


--
-- Name: documents_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX documents_creator_company_id_index ON public.documents USING btree (creator_company_id);


--
-- Name: documents_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX documents_status_id_index ON public.documents USING btree (status_id);


--
-- Name: documents_type_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX documents_type_id_index ON public.documents USING btree (type_id);


--
-- Name: entity_logs_log_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entity_logs_log_type_index ON public.entity_logs USING btree (log_type);


--
-- Name: entity_logs_model_type_model_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX entity_logs_model_type_model_id_index ON public.entity_logs USING btree (model_type, model_id);


--
-- Name: file_loads_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX file_loads_creator_company_id_index ON public.file_loads USING btree (creator_company_id);


--
-- Name: file_loads_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX file_loads_user_id_index ON public.file_loads USING btree (user_id);


--
-- Name: goods_adr_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_adr_id_index ON public.goods USING btree (adr_id);


--
-- Name: goods_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_creator_company_id_index ON public.goods USING btree (creator_company_id);


--
-- Name: goods_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_erp_id_index ON public.goods USING btree (erp_id);


--
-- Name: goods_measurement_unit_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_measurement_unit_id_index ON public.goods USING btree (measurement_unit_id);


--
-- Name: goods_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_status_id_index ON public.goods USING btree (status_id);


--
-- Name: goods_to_container_registers_container_register_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_to_container_registers_container_register_id_index ON public.goods_to_container_registers USING btree (container_register_id);


--
-- Name: goods_to_container_registers_leftover_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX goods_to_container_registers_leftover_id_index ON public.goods_to_container_registers USING btree (leftover_id);


--
-- Name: integrations_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX integrations_creator_company_id_index ON public.integrations USING btree (creator_company_id);


--
-- Name: inventories_creator_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_creator_id_index ON public.inventories USING btree (creator_id);


--
-- Name: inventories_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_erp_id_index ON public.inventories USING btree (erp_id);


--
-- Name: inventories_manufacturer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_manufacturer_id_index ON public.inventories USING btree (manufacturer_id);


--
-- Name: inventories_row_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_row_id_index ON public.inventories USING btree (row_id);


--
-- Name: inventories_sector_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_sector_id_index ON public.inventories USING btree (sector_id);


--
-- Name: inventories_supplier_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_supplier_id_index ON public.inventories USING btree (supplier_id);


--
-- Name: inventories_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_type_index ON public.inventories USING btree (type);


--
-- Name: inventories_warehouse_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_warehouse_erp_id_index ON public.inventories USING btree (warehouse_erp_id);


--
-- Name: inventories_warehouse_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_warehouse_id_index ON public.inventories USING btree (warehouse_id);


--
-- Name: inventories_zone_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventories_zone_id_index ON public.inventories USING btree (zone_id);


--
-- Name: inventory_items_cell_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_items_cell_id_index ON public.inventory_items USING btree (cell_id);


--
-- Name: inventory_items_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_items_status_index ON public.inventory_items USING btree (status);


--
-- Name: inventory_leftovers_inventory_item_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_leftovers_inventory_item_id_index ON public.inventory_leftovers USING btree (inventory_item_id);


--
-- Name: inventory_manual_leftover_logs_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_manual_leftover_logs_created_at_index ON public.inventory_manual_leftover_logs USING btree (created_at);


--
-- Name: inventory_manual_leftover_logs_executor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_manual_leftover_logs_executor_id_index ON public.inventory_manual_leftover_logs USING btree (executor_id);


--
-- Name: inventory_manual_leftover_logs_group_id_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_manual_leftover_logs_group_id_created_at_index ON public.inventory_manual_leftover_logs USING btree (group_id, created_at);


--
-- Name: inventory_manual_leftover_logs_group_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_manual_leftover_logs_group_id_index ON public.inventory_manual_leftover_logs USING btree (group_id);


--
-- Name: inventory_manual_leftover_logs_leftover_id_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX inventory_manual_leftover_logs_leftover_id_created_at_index ON public.inventory_manual_leftover_logs USING btree (leftover_id, created_at);


--
-- Name: invoice_documents_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoice_documents_document_id_index ON public.invoice_documents USING btree (document_id);


--
-- Name: invoice_documents_invoice_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoice_documents_invoice_id_index ON public.invoice_documents USING btree (invoice_id);


--
-- Name: invoices_company_customer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_company_customer_id_index ON public.invoices USING btree (company_customer_id);


--
-- Name: invoices_company_provider_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_company_provider_id_index ON public.invoices USING btree (company_provider_id);


--
-- Name: invoices_contract_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_contract_id_index ON public.invoices USING btree (contract_id);


--
-- Name: invoices_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_creator_company_id_index ON public.invoices USING btree (creator_company_id);


--
-- Name: invoices_responsible_receive_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_responsible_receive_id_index ON public.invoices USING btree (responsible_receive_id);


--
-- Name: invoices_responsible_supply_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_responsible_supply_id_index ON public.invoices USING btree (responsible_supply_id);


--
-- Name: leftovers_batch_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_batch_fulltext ON public.leftovers USING gin (to_tsvector('english'::regconfig, (batch)::text));


--
-- Name: leftovers_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_creator_company_id_index ON public.leftovers USING btree (creator_company_id);


--
-- Name: leftovers_erp_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_erp_creator_company_id_index ON public.leftovers_erp USING btree (creator_company_id);


--
-- Name: leftovers_erp_goods_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_erp_goods_erp_id_index ON public.leftovers_erp USING btree (goods_erp_id);


--
-- Name: leftovers_erp_warehouse_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_erp_warehouse_erp_id_index ON public.leftovers_erp USING btree (warehouse_erp_id);


--
-- Name: leftovers_goods_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_goods_id_index ON public.leftovers USING btree (goods_id);


--
-- Name: leftovers_package_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_package_id_index ON public.leftovers USING btree (package_id);


--
-- Name: leftovers_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_status_id_index ON public.leftovers USING btree (status_id);


--
-- Name: leftovers_warehouse_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX leftovers_warehouse_id_index ON public.leftovers USING btree (warehouse_id);


--
-- Name: locations_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX locations_company_id_index ON public.locations USING btree (company_id);


--
-- Name: locations_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX locations_creator_company_id_index ON public.locations USING btree (creator_company_id);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: obligation_adjustments_invoice_obligation_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX obligation_adjustments_invoice_obligation_id_index ON public.obligation_adjustments USING btree (invoice_obligation_id);


--
-- Name: operational_costs_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX operational_costs_company_id_index ON public.operational_costs USING btree (company_id);


--
-- Name: packages_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX packages_creator_company_id_index ON public.packages USING btree (creator_company_id);


--
-- Name: packages_erp_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX packages_erp_id_index ON public.packages USING btree (erp_id);


--
-- Name: packages_goods_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX packages_goods_id_index ON public.packages USING btree (goods_id);


--
-- Name: packages_type_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX packages_type_id_index ON public.packages USING btree (type_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: registers_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_creator_company_id_index ON public.registers USING btree (creator_company_id);


--
-- Name: registers_manager_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_manager_id_index ON public.registers USING btree (manager_id);


--
-- Name: registers_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_status_id_index ON public.registers USING btree (status_id);


--
-- Name: registers_storekeeper_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_storekeeper_id_index ON public.registers USING btree (storekeeper_id);


--
-- Name: registers_transport_planning_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_transport_planning_id_index ON public.registers USING btree (transport_planning_id);


--
-- Name: registers_warehouse_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX registers_warehouse_id_index ON public.registers USING btree (warehouse_id);


--
-- Name: regulations_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX regulations_creator_company_id_index ON public.regulations USING btree (creator_company_id);


--
-- Name: regulations_parent_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX regulations_parent_id_index ON public.regulations USING btree (parent_id);


--
-- Name: row_cell_info_cell_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX row_cell_info_cell_id_index ON public.row_cell_info USING btree (cell_id);


--
-- Name: rows_sector_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX rows_sector_id_index ON public.rows USING btree (sector_id);


--
-- Name: schedule_patterns_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX schedule_patterns_creator_company_id_index ON public.schedule_patterns USING btree (creator_company_id);


--
-- Name: sectors_warehouse_zone_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sectors_warehouse_zone_id_index ON public.sectors USING btree (zone_id);


--
-- Name: service_by_documents_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX service_by_documents_document_id_index ON public.service_by_documents USING btree (document_id);


--
-- Name: service_by_documents_service_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX service_by_documents_service_id_index ON public.service_by_documents USING btree (service_id);


--
-- Name: services_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX services_creator_company_id_index ON public.services USING btree (creator_company_id);


--
-- Name: sku_by_documents_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sku_by_documents_document_id_index ON public.goods_by_documents USING btree (document_id);


--
-- Name: task_items_container_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX task_items_container_id_index ON public.task_items USING btree (container_id);


--
-- Name: task_items_goods_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX task_items_goods_id_index ON public.task_items USING btree (goods_id);


--
-- Name: task_items_leftover_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX task_items_leftover_id_index ON public.task_items USING btree (leftover_id);


--
-- Name: task_items_local_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX task_items_local_id_index ON public.task_items USING btree (local_id);


--
-- Name: task_items_task_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX task_items_task_id_index ON public.task_items USING btree (task_id);


--
-- Name: tasks_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_creator_company_id_index ON public.tasks USING btree (creator_company_id);


--
-- Name: tasks_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_document_id_index ON public.tasks USING btree (document_id);


--
-- Name: tasks_kind_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_kind_index ON public.tasks USING btree (kind);


--
-- Name: tasks_local_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_local_id_index ON public.tasks USING btree (local_id);


--
-- Name: tasks_processing_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_processing_type_index ON public.tasks USING btree (processing_type);


--
-- Name: tasks_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_status_index ON public.tasks USING btree (status);


--
-- Name: tasks_type_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_type_id_index ON public.tasks USING btree (type_id);


--
-- Name: telescope_entries_batch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_batch_id_index ON public.telescope_entries USING btree (batch_id);


--
-- Name: telescope_entries_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_created_at_index ON public.telescope_entries USING btree (created_at);


--
-- Name: telescope_entries_family_hash_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_family_hash_index ON public.telescope_entries USING btree (family_hash);


--
-- Name: telescope_entries_tags_tag_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_tags_tag_index ON public.telescope_entries_tags USING btree (tag);


--
-- Name: telescope_entries_type_should_display_on_index_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_type_should_display_on_index_index ON public.telescope_entries USING btree (type, should_display_on_index);


--
-- Name: transport_models_brand_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_models_brand_id_index ON public._d_transport_models USING btree (brand_id);


--
-- Name: transport_planing_additional_equipment_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_additional_equipment_id_index ON public.transport_plannings USING btree (additional_equipment_id);


--
-- Name: transport_planing_company_carrier_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_company_carrier_id_index ON public.transport_plannings USING btree (company_carrier_id);


--
-- Name: transport_planing_company_provider_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_company_provider_id_index ON public.transport_plannings USING btree (company_provider_id);


--
-- Name: transport_planing_creator_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_creator_id_index ON public.transport_plannings USING btree (creator_id);


--
-- Name: transport_planing_documents_document_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_documents_document_id_index ON public.transport_planning_documents USING btree (document_id);


--
-- Name: transport_planing_documents_transport_planing_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_documents_transport_planing_id_index ON public.transport_planning_documents USING btree (transport_planing_id);


--
-- Name: transport_planing_driver_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_driver_id_index ON public.transport_plannings USING btree (driver_id);


--
-- Name: transport_planing_payer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_payer_id_index ON public.transport_plannings USING btree (payer_id);


--
-- Name: transport_planing_transport_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planing_transport_id_index ON public.transport_plannings USING btree (transport_id);


--
-- Name: transport_planning_to_statuses_status_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planning_to_statuses_status_id_index ON public.transport_planning_to_statuses USING btree (status_id);


--
-- Name: transport_planning_to_statuses_transport_planning_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_planning_to_statuses_transport_planning_id_index ON public.transport_planning_to_statuses USING btree (transport_planning_id);


--
-- Name: transport_plannings_additional_equipment_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_plannings_additional_equipment_id_index ON public.transport_plannings USING btree (additional_equipment_id);


--
-- Name: transport_plannings_company_carrier_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_plannings_company_carrier_id_index ON public.transport_plannings USING btree (company_carrier_id);


--
-- Name: transport_plannings_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_plannings_creator_company_id_index ON public.transport_plannings USING btree (creator_company_id);


--
-- Name: transport_plannings_driver_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_plannings_driver_id_index ON public.transport_plannings USING btree (driver_id);


--
-- Name: transport_plannings_transport_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transport_plannings_transport_id_index ON public.transport_plannings USING btree (transport_id);


--
-- Name: transports_brand_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_brand_id_index ON public.transports USING btree (brand_id);


--
-- Name: transports_category_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_category_id_index ON public.transports USING btree (category_id);


--
-- Name: transports_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_company_id_index ON public.transports USING btree (company_id);


--
-- Name: transports_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_creator_company_id_index ON public.transports USING btree (creator_company_id);


--
-- Name: transports_driver_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_driver_id_index ON public.transports USING btree (driver_id);


--
-- Name: transports_model_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_model_id_index ON public.transports USING btree (model_id);


--
-- Name: transports_type_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transports_type_id_index ON public.transports USING btree (type_id);


--
-- Name: user_working_data_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_working_data_company_id_index ON public.user_working_data USING btree (company_id);


--
-- Name: user_working_data_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_working_data_creator_company_id_index ON public.user_working_data USING btree (creator_company_id);


--
-- Name: user_working_data_position_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_working_data_position_id_index ON public.user_working_data USING btree (position_id);


--
-- Name: user_working_data_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_working_data_user_id_index ON public.user_working_data USING btree (user_id);


--
-- Name: user_working_data_workspace_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_working_data_workspace_id_index ON public.user_working_data USING btree (workspace_id);


--
-- Name: warehouse_zones_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouse_zones_creator_company_id_index ON public.warehouse_zones USING btree (creator_company_id);


--
-- Name: warehouse_zones_warehouse_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouse_zones_warehouse_id_index ON public.warehouse_zones USING btree (warehouse_id);


--
-- Name: warehouses_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouses_creator_company_id_index ON public.warehouses USING btree (creator_company_id);


--
-- Name: warehouses_erp_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouses_erp_creator_company_id_index ON public.warehouses_erp USING btree (creator_company_id);


--
-- Name: warehouses_location_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouses_location_id_index ON public.warehouses USING btree (location_id);


--
-- Name: warehouses_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX warehouses_user_id_index ON public.warehouses USING btree (user_id);


--
-- Name: workspaces_creator_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX workspaces_creator_company_id_index ON public.workspaces USING btree (creator_company_id);


--
-- Name: workspaces_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX workspaces_user_id_index ON public.workspaces USING btree (user_id);


--
-- Name: _d_roles _d_roles_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_roles
    ADD CONSTRAINT _d_roles_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: _d_task_types _d_task_types_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_task_types
    ADD CONSTRAINT _d_task_types_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: _d_zone_type_subtype _d_zone_type_subtype_zone_subtype_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_type_subtype
    ADD CONSTRAINT _d_zone_type_subtype_zone_subtype_id_foreign FOREIGN KEY (zone_subtype_id) REFERENCES public._d_zone_subtypes(id) ON DELETE CASCADE;


--
-- Name: _d_zone_type_subtype _d_zone_type_subtype_zone_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_zone_type_subtype
    ADD CONSTRAINT _d_zone_type_subtype_zone_type_id_foreign FOREIGN KEY (zone_type_id) REFERENCES public._d_zone_types(id) ON DELETE CASCADE;


--
-- Name: additional_equipment additional_equipment_adr_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_adr_id_foreign FOREIGN KEY (adr_id) REFERENCES public._d_adrs(id);


--
-- Name: additional_equipment additional_equipment_brand_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_brand_id_foreign FOREIGN KEY (brand_id) REFERENCES public._d_additional_equipment_brands(id);


--
-- Name: additional_equipment additional_equipment_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: additional_equipment additional_equipment_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_country_id_foreign FOREIGN KEY (country_id) REFERENCES public._d_countries(id);


--
-- Name: additional_equipment additional_equipment_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: additional_equipment additional_equipment_model_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_model_id_foreign FOREIGN KEY (model_id) REFERENCES public._d_additional_equipment_models(id);


--
-- Name: _d_additional_equipment_models additional_equipment_models_brand_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_additional_equipment_models
    ADD CONSTRAINT additional_equipment_models_brand_id_foreign FOREIGN KEY (brand_id) REFERENCES public._d_additional_equipment_brands(id);


--
-- Name: additional_equipment additional_equipment_transport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_transport_id_foreign FOREIGN KEY (transport_id) REFERENCES public.transports(id);


--
-- Name: additional_equipment additional_equipment_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.additional_equipment
    ADD CONSTRAINT additional_equipment_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_transport_types(id);


--
-- Name: address_details address_details_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address_details
    ADD CONSTRAINT address_details_country_id_foreign FOREIGN KEY (country_id) REFERENCES public._d_countries(id);


--
-- Name: address_details address_details_settlement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address_details
    ADD CONSTRAINT address_details_settlement_id_foreign FOREIGN KEY (settlement_id) REFERENCES public._d_settlements(id);


--
-- Name: address_details address_details_street_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.address_details
    ADD CONSTRAINT address_details_street_id_foreign FOREIGN KEY (street_id) REFERENCES public._d_streets(id);


--
-- Name: barcodes barcodes_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.barcodes
    ADD CONSTRAINT barcodes_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: barcodes barcodes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.barcodes
    ADD CONSTRAINT barcodes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: bookmarks bookmarks_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bookmarks
    ADD CONSTRAINT bookmarks_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: categories categories_goods_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_goods_category_id_foreign FOREIGN KEY (goods_category_id) REFERENCES public._d_goods_categories(id) ON DELETE SET NULL;


--
-- Name: categories categories_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.categories(id) ON DELETE SET NULL;


--
-- Name: categories categories_workspace_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES public.workspaces(id) ON DELETE SET NULL;


--
-- Name: cells cells_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cells
    ADD CONSTRAINT cells_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_cell_statuses(id);


--
-- Name: companies companies_address_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_address_id_foreign FOREIGN KEY (address_id) REFERENCES public.address_details(id) ON DELETE CASCADE;


--
-- Name: companies companies_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_category_id_foreign FOREIGN KEY (category_id) REFERENCES public._d_company_categories(id);


--
-- Name: companies companies_company_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_company_type_id_foreign FOREIGN KEY (company_type_id) REFERENCES public._d_company_types(id);


--
-- Name: companies companies_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id);


--
-- Name: companies companies_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_company_statuses(id);


--
-- Name: companies companies_workspace_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.companies
    ADD CONSTRAINT companies_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES public.workspaces(id) ON DELETE CASCADE;


--
-- Name: _d_company_categories company_categories_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_company_categories
    ADD CONSTRAINT company_categories_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: company_requests company_requests_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_requests
    ADD CONSTRAINT company_requests_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE CASCADE;


--
-- Name: company_requests company_requests_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_requests
    ADD CONSTRAINT company_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: company_to_workspaces company_to_workspaces_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_to_workspaces
    ADD CONSTRAINT company_to_workspaces_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: company_to_workspaces company_to_workspaces_workspace_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.company_to_workspaces
    ADD CONSTRAINT company_to_workspaces_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES public.workspaces(id);


--
-- Name: container_by_documents container_by_documents_container_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_by_documents
    ADD CONSTRAINT container_by_documents_container_id_foreign FOREIGN KEY (container_id) REFERENCES public.containers(id);


--
-- Name: container_by_documents container_by_documents_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_by_documents
    ADD CONSTRAINT container_by_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: container_registers container_registers_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_registers
    ADD CONSTRAINT container_registers_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: container_registers container_registers_container_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_registers
    ADD CONSTRAINT container_registers_container_id_foreign FOREIGN KEY (container_id) REFERENCES public.containers(id);


--
-- Name: container_registers container_registers_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.container_registers
    ADD CONSTRAINT container_registers_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: containers containers_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.containers
    ADD CONSTRAINT containers_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: containers containers_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.containers
    ADD CONSTRAINT containers_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_container_types(id);


--
-- Name: contracts_comments contracts_comments_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts_comments
    ADD CONSTRAINT contracts_comments_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE CASCADE;


--
-- Name: contracts_comments contracts_comments_contract_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts_comments
    ADD CONSTRAINT contracts_comments_contract_id_foreign FOREIGN KEY (contract_id) REFERENCES public.contracts(id) ON DELETE CASCADE;


--
-- Name: contracts contracts_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id) ON DELETE CASCADE;


--
-- Name: contracts contracts_company_regulation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_company_regulation_id_foreign FOREIGN KEY (company_regulation_id) REFERENCES public.regulations(id) ON DELETE CASCADE;


--
-- Name: contracts contracts_counterparty_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_counterparty_id_foreign FOREIGN KEY (counterparty_id) REFERENCES public.companies(id) ON DELETE CASCADE;


--
-- Name: contracts contracts_counterparty_regulation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_counterparty_regulation_id_foreign FOREIGN KEY (counterparty_regulation_id) REFERENCES public.regulations(id) ON DELETE CASCADE;


--
-- Name: contracts contracts_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: doctype_fields doctype_fields_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctype_fields
    ADD CONSTRAINT doctype_fields_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: document_leftover_reservations document_leftover_reservations_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_leftover_reservations
    ADD CONSTRAINT document_leftover_reservations_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE SET NULL;


--
-- Name: document_leftover_reservations document_leftover_reservations_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_leftover_reservations
    ADD CONSTRAINT document_leftover_reservations_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id) ON DELETE SET NULL;


--
-- Name: document_relations document_relations_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_relations
    ADD CONSTRAINT document_relations_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: document_relations document_relations_related_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_relations
    ADD CONSTRAINT document_relations_related_id_foreign FOREIGN KEY (related_id) REFERENCES public.documents(id);


--
-- Name: document_types document_types_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.document_types
    ADD CONSTRAINT document_types_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: documents documents_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: documents documents_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_document_statuses(id);


--
-- Name: documents documents_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_type_id_foreign FOREIGN KEY (type_id) REFERENCES public.document_types(id);


--
-- Name: documents documents_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id) ON DELETE CASCADE;


--
-- Name: entity_logs entity_logs_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entity_logs
    ADD CONSTRAINT entity_logs_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: entity_logs entity_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.entity_logs
    ADD CONSTRAINT entity_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: file_loads file_loads_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.file_loads
    ADD CONSTRAINT file_loads_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: file_loads file_loads_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.file_loads
    ADD CONSTRAINT file_loads_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: goods goods_adr_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_adr_id_foreign FOREIGN KEY (adr_id) REFERENCES public._d_adrs(id);


--
-- Name: goods goods_brand_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_brand_foreign FOREIGN KEY (brand) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: goods_by_documents goods_by_documents_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_by_documents
    ADD CONSTRAINT goods_by_documents_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id);


--
-- Name: goods goods_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: goods goods_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: goods_kit_items goods_kit_items_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_kit_items
    ADD CONSTRAINT goods_kit_items_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id) ON DELETE CASCADE;


--
-- Name: goods_kit_items goods_kit_items_goods_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_kit_items
    ADD CONSTRAINT goods_kit_items_goods_parent_id_foreign FOREIGN KEY (goods_parent_id) REFERENCES public.goods(id) ON DELETE CASCADE;


--
-- Name: goods_kit_items goods_kit_items_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_kit_items
    ADD CONSTRAINT goods_kit_items_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id);


--
-- Name: goods goods_manufacturer_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_manufacturer_country_id_foreign FOREIGN KEY (manufacturer_country_id) REFERENCES public._d_countries(id);


--
-- Name: goods goods_manufacturer_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_manufacturer_foreign FOREIGN KEY (manufacturer) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: goods goods_measurement_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_measurement_unit_id_foreign FOREIGN KEY (measurement_unit_id) REFERENCES public._d_measurement_units(id);


--
-- Name: goods goods_provider_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods
    ADD CONSTRAINT goods_provider_foreign FOREIGN KEY (provider) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: goods_to_container_registers goods_to_container_registers_container_register_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_to_container_registers
    ADD CONSTRAINT goods_to_container_registers_container_register_id_foreign FOREIGN KEY (container_register_id) REFERENCES public.container_registers(id);


--
-- Name: goods_to_container_registers goods_to_container_registers_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_to_container_registers
    ADD CONSTRAINT goods_to_container_registers_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id);


--
-- Name: income_document_leftovers income_document_leftovers_container_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_container_id_foreign FOREIGN KEY (container_id) REFERENCES public.container_registers(id) ON DELETE SET NULL;


--
-- Name: income_document_leftovers income_document_leftovers_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: income_document_leftovers income_document_leftovers_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE SET NULL;


--
-- Name: income_document_leftovers income_document_leftovers_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id) ON DELETE SET NULL;


--
-- Name: income_document_leftovers income_document_leftovers_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.income_document_leftovers
    ADD CONSTRAINT income_document_leftovers_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE SET NULL;


--
-- Name: integrations integrations_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.integrations
    ADD CONSTRAINT integrations_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: inventories inventories_brand_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_brand_foreign FOREIGN KEY (brand) REFERENCES public.companies(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: inventories inventories_category_subcategory_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_category_subcategory_foreign FOREIGN KEY (category_subcategory) REFERENCES public.categories(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: inventories inventories_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: inventories inventories_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id);


--
-- Name: inventories inventories_manufacturer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_manufacturer_id_foreign FOREIGN KEY (manufacturer_id) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: inventories inventories_performer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_performer_id_foreign FOREIGN KEY (performer_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: inventories inventories_row_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_row_id_foreign FOREIGN KEY (row_id) REFERENCES public.rows(id) ON DELETE SET NULL;


--
-- Name: inventories inventories_sector_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_sector_id_foreign FOREIGN KEY (sector_id) REFERENCES public.sectors(id) ON DELETE SET NULL;


--
-- Name: inventories inventories_supplier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_supplier_id_foreign FOREIGN KEY (supplier_id) REFERENCES public.companies(id) ON DELETE SET NULL;


--
-- Name: inventories inventories_warehouse_erp_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_warehouse_erp_id_foreign FOREIGN KEY (warehouse_erp_id) REFERENCES public.warehouses_erp(id) ON DELETE SET NULL;


--
-- Name: inventories inventories_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: inventories inventories_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventories
    ADD CONSTRAINT inventories_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES public.warehouse_zones(id) ON DELETE SET NULL;


--
-- Name: inventory_goods inventory_goods_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_goods
    ADD CONSTRAINT inventory_goods_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id) ON DELETE RESTRICT;


--
-- Name: inventory_goods inventory_goods_inventory_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_goods
    ADD CONSTRAINT inventory_goods_inventory_id_foreign FOREIGN KEY (inventory_id) REFERENCES public.inventories(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_inventory_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_inventory_id_foreign FOREIGN KEY (inventory_id) REFERENCES public.inventories(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_update_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_update_id_foreign FOREIGN KEY (update_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: inventory_leftovers inventory_leftovers_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: inventory_leftovers inventory_leftovers_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id);


--
-- Name: inventory_leftovers inventory_leftovers_inventory_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_inventory_item_id_foreign FOREIGN KEY (inventory_item_id) REFERENCES public.inventory_items(id) ON DELETE CASCADE;


--
-- Name: inventory_leftovers inventory_leftovers_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: inventory_leftovers inventory_leftovers_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_leftovers
    ADD CONSTRAINT inventory_leftovers_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id);


--
-- Name: inventory_manual_leftover_logs inventory_manual_leftover_logs_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_manual_leftover_logs
    ADD CONSTRAINT inventory_manual_leftover_logs_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id) ON DELETE CASCADE;


--
-- Name: inventory_performers inventory_performers_inventory_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_performers
    ADD CONSTRAINT inventory_performers_inventory_id_foreign FOREIGN KEY (inventory_id) REFERENCES public.inventories(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: inventory_performers inventory_performers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_performers
    ADD CONSTRAINT inventory_performers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: invoice_documents invoice_documents_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_documents
    ADD CONSTRAINT invoice_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: invoice_documents invoice_documents_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_documents
    ADD CONSTRAINT invoice_documents_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.invoices(id);


--
-- Name: invoices invoices_company_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_company_customer_id_foreign FOREIGN KEY (company_customer_id) REFERENCES public.companies(id);


--
-- Name: invoices invoices_company_provider_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_company_provider_id_foreign FOREIGN KEY (company_provider_id) REFERENCES public.companies(id);


--
-- Name: invoices invoices_contract_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_contract_id_foreign FOREIGN KEY (contract_id) REFERENCES public.contracts(id);


--
-- Name: invoices invoices_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: invoices invoices_responsible_receive_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_responsible_receive_id_foreign FOREIGN KEY (responsible_receive_id) REFERENCES public.companies(id);


--
-- Name: invoices invoices_responsible_supply_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_responsible_supply_id_foreign FOREIGN KEY (responsible_supply_id) REFERENCES public.companies(id);


--
-- Name: leftovers leftovers_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: leftovers leftovers_container_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_container_id_foreign FOREIGN KEY (container_id) REFERENCES public.container_registers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: leftovers leftovers_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: leftovers leftovers_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE SET NULL;


--
-- Name: leftovers_erp leftovers_erp_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers_erp
    ADD CONSTRAINT leftovers_erp_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: leftovers leftovers_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id);


--
-- Name: leftovers leftovers_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id);


--
-- Name: leftovers leftovers_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.leftovers
    ADD CONSTRAINT leftovers_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: legal_companies legal_companies_legal_address_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.legal_companies
    ADD CONSTRAINT legal_companies_legal_address_id_foreign FOREIGN KEY (legal_address_id) REFERENCES public.address_details(id) ON DELETE CASCADE;


--
-- Name: legal_companies legal_companies_legal_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.legal_companies
    ADD CONSTRAINT legal_companies_legal_type_id_foreign FOREIGN KEY (legal_type_id) REFERENCES public._d_legal_types(id);


--
-- Name: locations locations_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: locations locations_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_country_id_foreign FOREIGN KEY (country_id) REFERENCES public._d_countries(id);


--
-- Name: locations locations_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: locations locations_settlement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_settlement_id_foreign FOREIGN KEY (settlement_id) REFERENCES public._d_settlements(id);


--
-- Name: login_histories login_histories_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.login_histories
    ADD CONSTRAINT login_histories_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public._d_roles(id) ON DELETE CASCADE;


--
-- Name: obligation_adjustments obligation_adjustments_address_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.obligation_adjustments
    ADD CONSTRAINT obligation_adjustments_address_id_foreign FOREIGN KEY (address_id) REFERENCES public.address_details(id);


--
-- Name: obligation_adjustments obligation_adjustments_failure_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.obligation_adjustments
    ADD CONSTRAINT obligation_adjustments_failure_id_foreign FOREIGN KEY (failure_id) REFERENCES public._d_transport_planning_failure_types(id);


--
-- Name: obligation_adjustments obligation_adjustments_invoice_obligation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.obligation_adjustments
    ADD CONSTRAINT obligation_adjustments_invoice_obligation_id_foreign FOREIGN KEY (invoice_obligation_id) REFERENCES public.invoice_documents(id);


--
-- Name: obligation_adjustments obligation_adjustments_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.obligation_adjustments
    ADD CONSTRAINT obligation_adjustments_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_transport_planning_statuses(id);


--
-- Name: operational_costs operational_costs_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operational_costs
    ADD CONSTRAINT operational_costs_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: outcome_document_leftovers outcome_document_leftovers_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.outcome_document_leftovers
    ADD CONSTRAINT outcome_document_leftovers_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: outcome_document_leftovers outcome_document_leftovers_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.outcome_document_leftovers
    ADD CONSTRAINT outcome_document_leftovers_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE SET NULL;


--
-- Name: outcome_document_leftovers outcome_document_leftovers_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.outcome_document_leftovers
    ADD CONSTRAINT outcome_document_leftovers_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id) ON DELETE SET NULL;


--
-- Name: outcome_document_leftovers outcome_document_leftovers_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.outcome_document_leftovers
    ADD CONSTRAINT outcome_document_leftovers_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id);


--
-- Name: packages packages_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: packages packages_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id);


--
-- Name: packages packages_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_package_types(id);


--
-- Name: pallet_registers pallet_registers_pallet_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallet_registers
    ADD CONSTRAINT pallet_registers_pallet_id_foreign FOREIGN KEY (pallet_id) REFERENCES public.pallets(id);


--
-- Name: pallets pallets_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallets
    ADD CONSTRAINT pallets_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: pallets pallets_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pallets
    ADD CONSTRAINT pallets_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: registers registers_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: registers registers_download_method_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_download_method_id_foreign FOREIGN KEY (download_method_id) REFERENCES public._d_transport_downloads(id);


--
-- Name: registers registers_download_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_download_zone_id_foreign FOREIGN KEY (download_zone_id) REFERENCES public._d_download_zones(id);


--
-- Name: registers registers_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_manager_id_foreign FOREIGN KEY (manager_id) REFERENCES public.users(id);


--
-- Name: registers registers_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_register_statuses(id);


--
-- Name: registers registers_storekeeper_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_storekeeper_id_foreign FOREIGN KEY (storekeeper_id) REFERENCES public.users(id);


--
-- Name: registers registers_transport_planning_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_transport_planning_id_foreign FOREIGN KEY (transport_planning_id) REFERENCES public.transport_plannings(id) ON DELETE CASCADE;


--
-- Name: registers registers_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.registers
    ADD CONSTRAINT registers_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id) ON DELETE CASCADE;


--
-- Name: regulations regulations_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.regulations
    ADD CONSTRAINT regulations_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: regulations regulations_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.regulations
    ADD CONSTRAINT regulations_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.regulations(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public._d_roles(id) ON DELETE CASCADE;


--
-- Name: row_cell_info row_cell_info_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.row_cell_info
    ADD CONSTRAINT row_cell_info_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: rows rows_sector_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rows
    ADD CONSTRAINT rows_sector_id_foreign FOREIGN KEY (sector_id) REFERENCES public.sectors(id) ON DELETE CASCADE;


--
-- Name: schedule_exceptions schedule_exceptions_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_exceptions
    ADD CONSTRAINT schedule_exceptions_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_exception_types(id);


--
-- Name: schedule_exceptions schedule_exceptions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_exceptions
    ADD CONSTRAINT schedule_exceptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.user_working_data(id) ON DELETE CASCADE;


--
-- Name: schedule_exceptions schedule_exceptions_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_exceptions
    ADD CONSTRAINT schedule_exceptions_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: schedule_patterns schedule_patterns_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_patterns
    ADD CONSTRAINT schedule_patterns_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: schedules schedules_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.user_working_data(id) ON DELETE CASCADE;


--
-- Name: schedules schedules_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: sectors sectors_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sectors
    ADD CONSTRAINT sectors_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES public.warehouse_zones(id) ON DELETE CASCADE;


--
-- Name: service_by_documents service_by_documents_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_by_documents
    ADD CONSTRAINT service_by_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: service_by_documents service_by_documents_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_by_documents
    ADD CONSTRAINT service_by_documents_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id);


--
-- Name: services services_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: services services_service_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_service_category_id_foreign FOREIGN KEY (category_id) REFERENCES public._d_service_categories(id);


--
-- Name: _d_settlements settlements_region_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_settlements
    ADD CONSTRAINT settlements_region_id_foreign FOREIGN KEY (region_id) REFERENCES public._d_regions(id) ON DELETE CASCADE;


--
-- Name: goods_by_documents sku_by_documents_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.goods_by_documents
    ADD CONSTRAINT sku_by_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE CASCADE;


--
-- Name: task_items task_items_goods_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_items
    ADD CONSTRAINT task_items_goods_id_foreign FOREIGN KEY (goods_id) REFERENCES public.goods(id);


--
-- Name: task_items task_items_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_items
    ADD CONSTRAINT task_items_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id);


--
-- Name: task_items task_items_task_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.task_items
    ADD CONSTRAINT task_items_task_id_foreign FOREIGN KEY (task_id) REFERENCES public.tasks(id) ON DELETE CASCADE;


--
-- Name: tasks tasks_cell_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_cell_id_foreign FOREIGN KEY (cell_id) REFERENCES public.cells(id);


--
-- Name: tasks tasks_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: tasks tasks_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: tasks tasks_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_task_types(id);


--
-- Name: telescope_entries_tags telescope_entries_tags_entry_uuid_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries_tags
    ADD CONSTRAINT telescope_entries_tags_entry_uuid_foreign FOREIGN KEY (entry_uuid) REFERENCES public.telescope_entries(uuid) ON DELETE CASCADE;


--
-- Name: terminal_leftover_logs terminal_leftover_logs_container_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs
    ADD CONSTRAINT terminal_leftover_logs_container_id_foreign FOREIGN KEY (container_id) REFERENCES public.container_registers(id);


--
-- Name: terminal_leftover_logs terminal_leftover_logs_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs
    ADD CONSTRAINT terminal_leftover_logs_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id);


--
-- Name: terminal_leftover_logs terminal_leftover_logs_leftover_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs
    ADD CONSTRAINT terminal_leftover_logs_leftover_id_foreign FOREIGN KEY (leftover_id) REFERENCES public.leftovers(id);


--
-- Name: terminal_leftover_logs terminal_leftover_logs_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.terminal_leftover_logs
    ADD CONSTRAINT terminal_leftover_logs_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id);


--
-- Name: _d_transport_models transport_models_brand_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public._d_transport_models
    ADD CONSTRAINT transport_models_brand_id_foreign FOREIGN KEY (brand_id) REFERENCES public._d_transport_brands(id);


--
-- Name: transport_plannings transport_planing_additional_equipment_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_additional_equipment_id_foreign FOREIGN KEY (additional_equipment_id) REFERENCES public.additional_equipment(id);


--
-- Name: transport_plannings transport_planing_company_carrier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_company_carrier_id_foreign FOREIGN KEY (company_carrier_id) REFERENCES public.companies(id);


--
-- Name: transport_plannings transport_planing_company_provider_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_company_provider_id_foreign FOREIGN KEY (company_provider_id) REFERENCES public.companies(id);


--
-- Name: transport_plannings transport_planing_creator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_creator_id_foreign FOREIGN KEY (creator_id) REFERENCES public.users(id);


--
-- Name: transport_plannings transport_planing_driver_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES public.users(id);


--
-- Name: transport_plannings transport_planing_payer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_payer_id_foreign FOREIGN KEY (payer_id) REFERENCES public.companies(id);


--
-- Name: transport_plannings transport_planing_transport_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_planing_transport_id_foreign FOREIGN KEY (transport_id) REFERENCES public.transports(id);


--
-- Name: transport_planning_documents transport_planning_documents_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_documents
    ADD CONSTRAINT transport_planning_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE CASCADE;


--
-- Name: transport_planning_documents transport_planning_documents_transport_planing_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_documents
    ADD CONSTRAINT transport_planning_documents_transport_planing_id_foreign FOREIGN KEY (transport_planing_id) REFERENCES public.transport_plannings(id) ON DELETE CASCADE;


--
-- Name: transport_planning_failures transport_planning_failures_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_failures
    ADD CONSTRAINT transport_planning_failures_status_id_foreign FOREIGN KEY (status_id) REFERENCES public.transport_planning_to_statuses(id) ON DELETE CASCADE;


--
-- Name: transport_planning_failures transport_planning_failures_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_failures
    ADD CONSTRAINT transport_planning_failures_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_transport_planning_failure_types(id) ON DELETE SET NULL;


--
-- Name: transport_planning_to_statuses transport_planning_to_statuses_address_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_to_statuses
    ADD CONSTRAINT transport_planning_to_statuses_address_id_foreign FOREIGN KEY (address_id) REFERENCES public.address_details(id);


--
-- Name: transport_planning_to_statuses transport_planning_to_statuses_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_to_statuses
    ADD CONSTRAINT transport_planning_to_statuses_status_id_foreign FOREIGN KEY (status_id) REFERENCES public._d_transport_planning_statuses(id);


--
-- Name: transport_planning_to_statuses transport_planning_to_statuses_transport_planning_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_planning_to_statuses
    ADD CONSTRAINT transport_planning_to_statuses_transport_planning_id_foreign FOREIGN KEY (transport_planning_id) REFERENCES public.transport_plannings(id) ON DELETE CASCADE;


--
-- Name: transport_plannings transport_plannings_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transport_plannings
    ADD CONSTRAINT transport_plannings_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: transports transports_adr_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_adr_id_foreign FOREIGN KEY (adr_id) REFERENCES public._d_adrs(id);


--
-- Name: transports transports_brand_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_brand_id_foreign FOREIGN KEY (brand_id) REFERENCES public._d_transport_brands(id);


--
-- Name: transports transports_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_category_id_foreign FOREIGN KEY (category_id) REFERENCES public._d_transport_categories(id);


--
-- Name: transports transports_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: transports transports_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: transports transports_driver_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES public.users(id);


--
-- Name: transports transports_equipment_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_equipment_id_foreign FOREIGN KEY (equipment_id) REFERENCES public.additional_equipment(id);


--
-- Name: transports transports_model_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_model_id_foreign FOREIGN KEY (model_id) REFERENCES public._d_transport_models(id);


--
-- Name: transports transports_registration_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_registration_country_id_foreign FOREIGN KEY (registration_country_id) REFERENCES public._d_countries(id);


--
-- Name: transports transports_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.transports
    ADD CONSTRAINT transports_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_transport_types(id);


--
-- Name: user_working_data user_working_data_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.companies(id);


--
-- Name: user_working_data user_working_data_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: user_working_data user_working_data_current_warehouse_app_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_current_warehouse_app_id_foreign FOREIGN KEY (current_warehouse_app_id) REFERENCES public.warehouses(id) ON DELETE SET NULL;


--
-- Name: user_working_data user_working_data_current_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_current_warehouse_id_foreign FOREIGN KEY (current_warehouse_id) REFERENCES public.warehouses(id) ON DELETE SET NULL;


--
-- Name: user_working_data user_working_data_position_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_position_id_foreign FOREIGN KEY (position_id) REFERENCES public._d_positions(id);


--
-- Name: user_working_data user_working_data_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_role_id_foreign FOREIGN KEY (role_id) REFERENCES public._d_roles(id) ON DELETE SET NULL;


--
-- Name: user_working_data user_working_data_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: user_working_data_warehouse user_working_data_warehouse_user_working_data_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data_warehouse
    ADD CONSTRAINT user_working_data_warehouse_user_working_data_id_foreign FOREIGN KEY (user_working_data_id) REFERENCES public.user_working_data(id) ON DELETE CASCADE;


--
-- Name: user_working_data_warehouse user_working_data_warehouse_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data_warehouse
    ADD CONSTRAINT user_working_data_warehouse_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id) ON DELETE CASCADE;


--
-- Name: user_working_data user_working_data_workspace_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_working_data
    ADD CONSTRAINT user_working_data_workspace_id_foreign FOREIGN KEY (workspace_id) REFERENCES public.workspaces(id);


--
-- Name: users users_current_workspace_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_current_workspace_id_foreign FOREIGN KEY (current_workspace_id) REFERENCES public.workspaces(id) ON DELETE CASCADE;


--
-- Name: warehouse_erp_assignments warehouse_erp_assignments_warehouse_erp_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_erp_assignments
    ADD CONSTRAINT warehouse_erp_assignments_warehouse_erp_id_foreign FOREIGN KEY (warehouse_erp_id) REFERENCES public.warehouses_erp(id) ON DELETE CASCADE;


--
-- Name: warehouse_erp_assignments warehouse_erp_assignments_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_erp_assignments
    ADD CONSTRAINT warehouse_erp_assignments_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id) ON DELETE CASCADE;


--
-- Name: warehouse_zones warehouse_zones_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_zones
    ADD CONSTRAINT warehouse_zones_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: warehouse_zones warehouse_zones_warehouse_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_zones
    ADD CONSTRAINT warehouse_zones_warehouse_id_foreign FOREIGN KEY (warehouse_id) REFERENCES public.warehouses(id);


--
-- Name: warehouse_zones warehouse_zones_zone_subtype_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_zones
    ADD CONSTRAINT warehouse_zones_zone_subtype_foreign FOREIGN KEY (zone_subtype) REFERENCES public._d_zone_subtypes(id) ON DELETE SET NULL;


--
-- Name: warehouse_zones warehouse_zones_zone_type_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouse_zones
    ADD CONSTRAINT warehouse_zones_zone_type_foreign FOREIGN KEY (zone_type) REFERENCES public._d_zone_types(id) ON DELETE SET NULL;


--
-- Name: warehouses warehouses_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: warehouses_erp warehouses_erp_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses_erp
    ADD CONSTRAINT warehouses_erp_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: warehouses warehouses_location_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_location_id_foreign FOREIGN KEY (location_id) REFERENCES public.locations(id);


--
-- Name: warehouses warehouses_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_type_id_foreign FOREIGN KEY (type_id) REFERENCES public._d_warehouse_types(id);


--
-- Name: warehouses warehouses_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: warehouses warehouses_warehouses_erp_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.warehouses
    ADD CONSTRAINT warehouses_warehouses_erp_id_foreign FOREIGN KEY (warehouse_erp_id) REFERENCES public.warehouses_erp(id);


--
-- Name: workspaces workspaces_creator_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.workspaces
    ADD CONSTRAINT workspaces_creator_company_id_foreign FOREIGN KEY (creator_company_id) REFERENCES public.companies(id);


--
-- Name: workspaces workspaces_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.workspaces
    ADD CONSTRAINT workspaces_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict LAX0b2p2bTkEWHhDwIHTcff9J1KiGRUvLpqO1bCZ0e8R2ZvEOG4iO6hvT7Wopmu

--
-- PostgreSQL database dump
--

\restrict cHcKYIT7QJxRjR9qqdxQGqlNHuAbQKo729UxMldekALRYOneL7qP3E41YgNN2WF

-- Dumped from database version 13.23 (Debian 13.23-1.pgdg13+1)
-- Dumped by pg_dump version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)

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

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0000_00_00_000000_create_websockets_statistics_entries_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2018_08_08_100000_create_telescope_entries_table	1
4	2019_08_19_000000_create_failed_jobs_table	1
5	2019_12_14_000001_create_personal_access_tokens_table	1
6	2022_11_04_160949_create_units_table	1
7	2022_11_04_161037_create_brigades_table	1
8	2022_11_17_120235_create_legal_types_table	1
9	2022_11_17_185944_create_roles_table	1
10	2022_11_17_185959_create_user_statuses_table	1
11	2022_11_17_190105_create_countries_table	1
12	2022_11_17_192340_create_settlements_table	1
13	2022_11_17_192359_create_streets_table	1
14	2022_11_17_192523_create_company_types_table	1
15	2022_11_17_192532_create_company_statuses_table	1
16	2022_11_17_192547_create_warehouse_types_table	1
17	2022_11_17_192602_create_storage_types_table	1
18	2022_11_17_192635_create_cell_statuses_table	1
19	2022_11_17_192657_create_exception_types_table	1
20	2022_11_17_192723_create_measurement_units_table	1
21	2022_11_18_052158_create_s_k_u_categories_table	1
22	2022_11_18_091026_create_positions_table	1
23	2022_11_18_092829_create_address_details_table	1
24	2022_11_18_092855_create_legal_companies_table	1
25	2022_11_18_092904_create_physical_companies_table	1
26	2022_11_18_093004_create_cells_table	1
27	2022_11_18_093112_create_sku_table	1
28	2022_11_18_093132_create_packagings_table	1
29	2022_11_18_093146_create_s_k_u_modifications_table	1
30	2022_11_18_100000_create_users_table	1
31	2022_11_18_102332_create_companies_table	1
32	2022_11_18_152456_create_barcodes_table	1
33	2022_11_19_092942_create_warehouses_table	1
34	2022_11_19_092952_create_sectors_table	1
35	2022_11_19_092958_create_rows_table	1
36	2022_11_19_093017_create_schedules_table	1
37	2022_11_19_093209_create_pallets_table	1
38	2022_11_19_093215_create_pallet_registers_table	1
39	2022_11_19_192457_create_login_histories_table	1
40	2022_12_07_102847_create_schedule_exceptions_table	1
41	2022_12_12_100217_create_schedule_patterns_table	1
42	2022_12_22_085224_create_transport_brands_table	1
43	2022_12_22_085233_create_transport_models_table	1
44	2022_12_22_085356_create_transport_kinds_table	1
45	2022_12_22_085403_create_transport_types_table	1
46	2022_12_22_090551_create_transport_downloads_table	1
47	2022_12_22_112251_create_additional_equipment_brands_table	1
48	2022_12_22_112300_create_additional_equipment_models_table	1
49	2022_12_22_112331_create_adrs_table	1
50	2022_12_22_112344_create_additional_equipment_table	1
51	2022_12_22_1295159_create_transports_table	1
52	2023_01_04_151511_create_user_to_companies_table	1
53	2023_01_31_111134_create_package_types_table	1
54	2023_01_31_114901_create_packages_table	1
55	2023_02_24_114816_create_bookmarks_table	1
56	2023_03_02_125951_create_download_zones_table	1
57	2023_03_02_142548_create_register_statuses_table	1
58	2023_03_03_125410_create_registers_table	1
59	2023_03_27_153730_create_doctype_statuses_table	1
60	2023_03_27_153758_create_document_types_table	1
61	2023_04_04_172528_create_doctype_fields_table	1
62	2023_04_11_145455_create_document_statuses_table	1
63	2023_04_12_115230_create_documents_table	1
64	2023_04_23_155012_create_sku_by_documents_table	1
65	2023_05_09_101500_create_workspaces_table	1
66	2023_05_10_103558_create_workspace_requests_table	1
67	2023_05_11_104056_create_user_workspaces_table	1
68	2023_05_16_101945_alter_users_make_phone_unique	1
69	2023_05_16_181625_add_field_to_companies	1
70	2023_05_17_143830_add_column_to_legal_companies	1
71	2023_05_18_123121_add_column_to_companies_table	1
72	2023_05_18_160944_change_column_in_legal_companies_table	1
73	2023_05_19_075228_create_verification_codes_table	1
74	2023_05_22_173145_refactor_workspace_relations	1
75	2023_05_22_175152_refactor_workspace_requsests_table	1
76	2023_05_22_210335_alter_users_add_current_workspace_column	1
77	2023_05_23_124836_alter_companies_make_workspace_nullable	1
78	2023_06_13_111517_create_document_relations_table	1
79	2023_07_10_140346_add_key_fields_to_dictionaries	1
80	2023_07_12_005933_create_transport_planing_table	1
81	2023_07_12_024634_create_integrations_table	1
82	2023_07_12_031847_create_goods_table	1
83	2023_07_12_034332_create_leftovers_table	1
84	2023_07_12_051200_create_service_categories_table	1
85	2023_07_12_051205_create_services_table	1
86	2023_07_24_071701_create_containers_table	1
87	2023_07_24_081441_create_regulations_table	1
88	2023_07_25_155248_create_container_by_documents_table	1
89	2023_07_25_155312_create_service_by_documents_table	1
90	2023_07_27_174533_alter_additional_equipments_drop_weight	1
91	2023_07_27_190251_alter_service_categories_add_key_column	1
92	2023_07_27_193726_alter_service_add_is_draft_column	1
93	2023_07_27_202307_refactor_container_tables	1
94	2023_07_28_025050_alter_transport_planning_add_comment_column	1
95	2023_07_28_042518_alter_document_types_add_key_column	1
96	2023_08_01_170057_change_column_name_in_services_table	1
97	2023_08_02_173204_create_company_to_workspaces_table	1
98	2023_08_03_145111_update_user_entity_migration	1
99	2023_08_03_174018_add_columns_to_users_table	1
100	2023_08_03_174608_create_user_to_workspaces_table	1
101	2023_08_04_134848_add_columns_to_schedule_patterns_table	1
102	2023_08_04_143026_separation_of_workspaces_migration	1
103	2023_08_08_101608_create_warehouse_erp_table	1
104	2023_08_08_111401_create_warehouse_to_erp_table	1
105	2023_08_08_175410_change_warehouses_table	1
106	2023_08_08_212900_alter_regulations_make_parent_id_nullable	1
107	2023_08_09_104142_change_transport_fields	1
108	2023_08_09_155128_add_fields_to_users_table	1
109	2023_08_10_143948_change_companies_table	1
110	2023_08_11_132705_update_avatar_type_column_in_worspaces_table	1
111	2023_08_15_104737_alter_transport_planning_add_statuses	1
112	2023_08_16_043829_alter_rename_transport_planing_documents_table	1
113	2023_08_16_063015_alter_transport_planning_statuses_add_problem_columns	1
114	2023_08_16_101248_alter_transport_planning_refactor_relations	1
115	2023_08_17_202133_create_transport_planning_status_failures_table	1
116	2023_08_18_003004_refactor_goods_relations	1
117	2023_08_18_064839_refactor_transport_planning_documents_relations	1
118	2023_08_22_183133_refactor_sku_by_documents_relations	1
119	2023_08_22_190736_alter_leftovers_add_consignment_column	1
120	2023_08_23_170648_yarych_makes_fields_nullable	1
121	2023_08_31_135052_create_regions_table	1
122	2023_09_04_123741_alter_regulations_change_relations	1
123	2023_09_05_114346_create_company_categories_table	1
124	2023_09_14_100224_change_transport_category_table	1
125	2023_09_15_173949_alter_registers_add_colemn_warehouse_id	1
126	2023_09_19_161308_add_workspace_id_to_dictionaries	1
127	2023_09_20_183603_change_users_flow_migration	1
128	2023_10_04_102225_create_additional_equipment_types_table	1
129	2023_10_04_111939_add_column_to_company_categories_table	1
130	2023_10_04_144357_alter_packages_add_default_column	1
131	2023_10_05_105910_change_company_and_leftovers	1
132	2023_10_16_184841_add_workspace_id_to_documents_table	1
133	2023_10_17_155617_create_file_loads_table	1
134	2023_10_18_182532_change_containers_table	1
135	2023_10_20_144553_create_cargo_types_table	1
136	2023_10_20_144602_create_delivery_types_table	1
137	2023_10_27_000935_create_contracts_table	1
138	2023_10_27_005807_create_permission_tables	1
139	2023_10_27_180435_create_operational_costs_table	1
140	2023_11_09_154637_create_invoices_table	1
141	2023_11_09_155335_create_invoice_documents_table	1
142	2023_11_09_155739_create_obligation_adjustments_table	1
143	2023_11_09_163416_alter_invoices_add_workspace_id_column	1
144	2023_11_09_164338_alter_invoices_add_status_id_column	1
145	2023_11_09_204739_add_is_reserved_field_to_transport_planning_table	1
146	2023_11_13_133615_add_additional_properties_field_to_documents_table	1
147	2023_11_13_173840_add_soft_deletes_to_tables	1
148	2023_11_14_174615_change_sku_categories_table	1
149	2023_12_08_131334_add_parent_id_to_sku_categories_table	1
150	2023_12_12_104203_drop_role_column_from_user_to_company_table	1
151	2023_12_12_121906_drop_user_to_workspaces_table	1
152	2023_12_18_130613_add_column_to_goods_table	1
153	2024_01_10_155839_add_deleted_at_in_document_types_table	1
154	2024_01_12_115408_edit_transport_plannings_and_documents_table	1
155	2024_01_29_193851_fix_transport_plannings_table	1
156	2025_07_04_110127_separate_data_by_companies	1
157	2025_07_08_154527_change_dictionaries_name	1
158	2025_07_09_153056_create_sku_table	1
159	2025_07_09_153652_change_leftovers_logic	1
160	2025_07_10_111556_fix_doctype_fields_table	1
161	2025_07_10_164438_bug_fixes_migration	1
162	2025_07_14_204814_fix_warehouse_migration	1
163	2025_07_15_164307_fix_user_role	1
164	2025_07_24_125857_change_containers_table	1
165	2025_07_28_100409_add_packages_table	1
166	2025_07_28_190951_remove_field_from_users	1
167	2025_07_30_003729_add_goods_table	1
168	2025_07_31_130032_remove_local_id_from_companies_table	1
169	2025_08_01_193402_change_leftovers_table	1
170	2025_08_04_105020_fix_local_id_in_containers_table	1
171	2025_08_06_113713_make_pagkage_count_nullable	1
173	2025_08_06_173121_add_changes_to_goods_table	2
174	2025_08_07_140855_fix_barcodes_table	3
177	2025_08_07_163912_add_locations_table	4
183	2025_08_11_134726_add_container_register_table	5
184	2025_08_19_112128_add_ware	6
185	2025_08_20_125159_add_changes_to_container_register	7
188	2025_08_26_102552_warehouse_erp_changes	8
189	2025_08_26_165752_create_warehouse_zones_table	9
190	2025_07_20_163814_change_rows_table	10
191	2025_07_21_095324_drop_cell_allocation_table	11
192	2025_08_28_143457_drop_cell_allocations_table	12
198	2025_08_28_170926_drop_fields_from_warehouse	13
200	2025_09_01_145001_change_id_to_uuid_in_rows	14
203	2025_09_05_003938_rename_column_in_sectors	15
204	2025_09_05_155902_add_provider_to_goods	16
205	2025_09_09_153712_add_field_to_leftovers	17
206	2025_09_11_232807_change_foreign_in_leftovers	18
207	2025_09_12_181954_change_fields_in_locations	19
208	2025_09_15_125816_add_img_type_to_goods	20
214	2025_09_17_162154_change_date_columns_in_goods	23
221	2025_09_16_130942_create_d_task_types_table	24
222	2025_09_16_132726_create_tasks_table	25
223	2025_09_16_155000_create_task_items_table	25
224	2025_09_22_192547_changes_in_packages	26
225	2025_09_19_123826_create_inventories_table	27
226	2025_09_23_162444_change_warehouse_components	28
227	2025_09_24_185711_fix_warehouse_code_range	29
228	2025_09_26_145106_clear_documents	30
229	2025_09_22_132241_create_inventory_items_table	31
230	2025_09_23_193512_recreate_performer_id_on_inventories_table	31
231	2025_09_25_173807_create_categories_table	31
232	2025_09_19_125502_add_admin_role	32
233	2025_09_29_165651_add_categories_id_to_goods_table	32
234	2025_09_29_172657_add_pin_fields_to_users_table	32
235	2025_10_01_161926_change_container_registers_status	33
236	2025_10_01_005338_alter_inventory_items_for_cells	34
237	2025_10_01_164435_add_update_id_to_inventory_items_table	34
238	2025_10_01_171419_alter_start_end_date_nullable_on_inventories_table	34
239	2025_10_02_220624_remove_cell_type_in_cells	34
240	2025_10_01_235001_create_inventory_performers_table	35
241	2025_10_02_191706_update_leftovers_status_check_add_3	35
242	2025_10_02_223422_fix_document_fields	36
243	2025_10_02_152858_add_pin_to_users_table	37
244	2025_10_03_154546_fix_packages	38
245	2025_10_03_163216_recreate_status_id_enum_on_leftovers_table	39
246	2025_10_07_154814_fix_url_in_locatiions	39
247	2025_10_10_131012_refactor_inventory_items_and_create_inventory_leftovers	40
248	2025_10_14_145308_add_unpackage_to_leftovers	40
249	2025_10_14_110936_add_priority_to_inventories_table	41
250	2025_10_14_160953_add_current_leftovers_to_inventory_leftovers_table	41
251	2025_10_15_182311_add_document_kind_in_document_types	42
252	2025_10_16_122849_add_warehouse_id_to_documents	43
253	2025_10_15_141808_add_expiration_term_and_container_registers_id_to_inventory_leftovers_table	44
254	2025_10_15_183617_drop_categories_id_and_add_category_id_uuid_to_goods_table	44
255	2025_10_17_160943_create_task_log_table	44
256	2025_10_20_160948_add_status_to_inventory_items_table	44
257	2025_10_22_155851_create_entity_logs_table	44
258	2025_10_23_132214_update_inventory_leftovers_make_current_leftovers_nullable	45
261	2025_10_23_163636_create_income_document_leftovers_table	46
262	2025_10_23_174449_drop_leftover_to_container_registers_table	46
265	2025_10_24_160755_add_field_to_income_document_leftovers	47
266	2025_10_26_192912_create_outcome_leftovers_logs_table	47
267	2025_10_27_124555_update_inventory_category	48
268	2025_10_27_150037_add_erp_id_to_goods_table	48
269	2025_10_27_150406_add_erp_id_to_companies_table	48
270	2025_10_27_150542_add_erp_id_to_categories_table	48
271	2025_10_27_150754_add_erp_id_to_inventories_table	48
272	2025_10_27_150850_add_erp_id_to_packages_table	48
273	2025_10_27_190600_update_inventory_brand	48
274	2025_10_28_004922_add_condition_to_inventory_leftovers_table	48
275	2025_10_29_120435_change_leftovers	48
276	2025_10_30_152046_change_validation_company_table	48
277	2025_10_31_122635_create_goods_kit_items_table	48
278	2025_10_31_133156_update_validation_goods_table	48
279	2025_11_03_152355_update_goods_kit_items_table	48
280	2025_11_03_183404_add_area_to_inventory_leftovers_table	48
281	2025_11_03_191107_add_area_to_inventory_items_table	48
282	2025_11_05_161422_add_soft_delete_wh_components	48
283	2025_11_06_144001_add_task_changes	49
284	2025_11_06_135251_create_user_working_data_warehouse_table	50
285	2025_11_07_150253_create_inventory_manual_leftover_logs_table	50
286	2025_11_10_135934_add_current_warehouse_id_to_user_working_data_table	50
287	2025_11_11_184308_add_group_columns_to_inventory_manual_leftover_logs	51
288	2025_11_12_194201_alter_leftovers_local_id_to_identity	51
289	2025_11_13_151413_add_status_to_cells_table	51
290	2025_11_14_103756_change_morph_types	51
291	2025_11_17_174014_create_leftovers_erp_table	52
292	2025_11_18_133225_fix_packages	53
293	2025_11_18_164023_update_types_for_leftovers_erp	54
294	2025_11_18_190117_add_current_warehouse_app_id_to_user_working_data_table	54
295	2025_11_19_171748_add_code_to_sectors	55
296	2025_11_20_114321_fix_goods_migration	56
297	2025_11_20_152404_add_cell_props_to_rows	57
298	2025_11_21_101658_changes_in_packages	58
299	2025_11_20_193711_add_real_docnames_to_legal_companies	59
300	2025_11_24_110902_alter_provider_manufacturer_brand_columns_on_goods_table	59
301	2025_11_25_182101_alter_type_id_nullable_on_warehouses_table	60
302	2025_11_28_112239_update_local_id_leftovers_erp	60
303	2025_12_03_114615_store-areas	60
304	2025_12_03_114749_zone-subtypes	60
305	2025_12_03_120052_zone_types_relation	60
306	2025_12_03_134006_warehouse_zones_types	60
307	2025_12_09_110610_change_in_documents	60
308	2025_12_10_223914_add_column_to_income_leftovers	61
309	2025_12_11_204441_change_status_to_string_in_tasks_table	62
310	2025_12_16_143155_drop_status_id_in_containers	63
311	2025_12_08_220351_alter_d_package_types_name_length	64
312	2025_12_08_223728_add_unique_to_d_package_types_key	64
313	2025_12_22_150800_create_warehouse_erp_assignments_table	64
314	2026_01_06_114442_add_changes_to_tasks	64
315	2026_01_06_172959_add_creation_type_to_tasks	65
316	2026_01_12_121422_fix_tasks_local_id	66
317	2026_01_12_094341_update_warehouse_type_id	67
318	2026_01_19_180849_add_reserved_to_leftovers	67
319	2026_01_20_115828_change_kind_in_document_types	68
320	2026_01_23_112641_fix_migration	69
321	2026_01_23_144623_document_leftover_reservations	70
322	2026_01_23_195417_change_kind_in_document_structures	71
325	2026_01_29_110626_terminal_reserve_leftover_logs	72
326	2026_02_04_214735_add_fields_to_terminal_leftover_logs	73
327	2026_02_04_225018_change_document_types_kind_to_string	73
328	2026_01_22_110842_create_table_inventory_goods	74
329	2026_01_22_152252_drop_nomenclature_id_from_inventories	74
330	2026_02_02_173704_update_d_company_statuses_name_length	74
331	2026_02_02_174105_update_d_additional_equipment_brands_name_length	74
332	2026_02_05_094216_update_d_additional_equipment_models_name_length	74
333	2026_02_05_094520_update_d_transport_categories_name_length	74
334	2026_02_05_094812_update_d_countries_name_length	74
335	2026_02_05_095559_update_d_exception_types_name_length	74
336	2026_02_05_111139_update_d_transport_downloads_name_length	74
337	2026_02_05_112656_update_d_company_types_name_length	74
338	2026_02_05_112901_update_d_measurement_units_name_length	74
339	2026_02_05_113131_update_d_goods_categories_name_length	74
340	2026_02_05_113305_update_d_doctype_statuses_name_length	74
341	2026_02_05_113426_update_d_register_statuses_name_length	74
342	2026_02_05_113629_update_d_cargo_types_name_length	74
343	2026_02_05_113803_update_d_task_types_name_length	74
344	2026_02_05_113952_update_d_legal_types_name_length	74
345	2026_02_05_114641_update_d_zone_subtypes_name_length	74
346	2026_02_05_115227_update_d_package_types_name_length	74
347	2026_02_05_120320_update_d_warehouse_types_name_length	74
348	2026_02_05_121927_change_field_name_to_json_for_dictionary_tables	75
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 348, true);


--
-- PostgreSQL database dump complete
--

\unrestrict cHcKYIT7QJxRjR9qqdxQGqlNHuAbQKo729UxMldekALRYOneL7qP3E41YgNN2WF

