

--new_api_user :: view  --
-- public.new_api_user source

-- public.new_api_user source

CREATE OR REPLACE VIEW public.new_api_user
AS SELECT t.id * floor(random() * (1000 - 100 + 1)::double precision + 100::double precision)::integer AS id,
    t.code,
    t.email AS mail,
    t.email AS username,
    t.password,
    t.role::text AS role,
    to_json('ROLE_TEAM'::text) AS roles
   FROM team t
  WHERE t.is_activated = true
UNION all
-- municipality president only 
 SELECT ma.id * floor(random() * (1000 - 100 + 1)::double precision + 100::double precision)::integer AS id,
    ma.code,
    ma.email AS mail,
    ma.email AS username,
    ma.password,
    ma.role::text AS role,
    to_json('ROLE_MUNICIPALITY'::text) AS roles
   FROM municipality_agent ma inner join municipality m on ( m.id =ma.municipality_id )
   where m.is_activated = true and ma.is_activated =true and ma."role" ='ROLE_MUNICIPALITY_PRESIDENT' and current_date <ma.date_end 
    -- enum type for citizent id type 

    CREATE TYPE citizent_id_type AS ENUM ('identityCard', 'passport', 'residenceCard');



