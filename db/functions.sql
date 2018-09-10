
DELIMITER $$
DROP FUNCTION IF EXISTS vincenty$$
CREATE FUNCTION vincenty(
	lat1 FLOAT, lon1 FLOAT,
	lat2 FLOAT, lon2 FLOAT
) RETURNS FLOAT
NO SQL
DETERMINISTIC
	COMMENT 'Returns the distance in degrees on the
             Earth between two known points
             of latitude and longitude
             using the Vincenty formula
             from http://en.wikipedia.org/wiki/Great-circle_distance'
	BEGIN
		RETURN  DEGREES(
				ATAN2(
						SQRT(
								POW(COS(RADIANS(lat2))*SIN(RADIANS(lon2-lon1)),2) +
								POW(COS(RADIANS(lat1))*SIN(RADIANS(lat2)) -
								    (SIN(RADIANS(lat1))*COS(RADIANS(lat2)) *
								     COS(RADIANS(lon2-lon1))) ,2)),
						SIN(RADIANS(lat1))*SIN(RADIANS(lat2)) +
						COS(RADIANS(lat1))*COS(RADIANS(lat2))*COS(RADIANS(lon2-lon1))));
	END$$
DELIMITER ;


-- ----------------------------------------


DELIMITER $$
DROP FUNCTION IF EXISTS geodistance$$
CREATE FUNCTION geodistance ( lat1 float, long1 float,
                              lat2 float, long2 float )
	RETURNS float
LANGUAGE SQL
DETERMINISTIC
CONTAINS SQL
	SQL SECURITY INVOKER
	BEGIN
		RETURN acos(  cos(radians( lat1 ))
		              * cos(radians( lat2 ))
		              * cos(radians( long1 ) - radians( long2 ))
		              + sin(radians( lat1 ))
		                * sin(radians( lat2 ))
		);
	END$$
DELIMITER ;
