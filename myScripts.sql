

--new_api_user :: view  --
CREATE OR REPLACE VIEW public.new_api_user
AS SELECT t.id * floor(random() * (1000 - 100 + 1)::double precision + 100::double precision)::integer AS id,
    t.code,
    t.email as mail,
    t.email as username,
    t.password,
    t.roles::text,
    t."role"::text
   FROM "admin"   t

  WHERE t.is_activated = true
UNION ALL
 SELECT ma.id * floor(random() * (1000 - 100 + 1)::double precision + 100::double precision)::integer AS id,
  	ma.code,
  	ma.email as mail,
    ma.email  as username,
    ma.password,
    ma.roles::text,
    ma."role"::text
   FROM municipality_agent ma

  WHERE ma.is_activated = true


    -- enum type for citizent id type 
    CREATE TYPE citizent_id_type AS ENUM ('identityCard', 'passport', 'residenceCard');



