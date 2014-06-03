<?php
function find_airline( $departure, $destination, $ordered_by, $ordered_how ) {
  return
    "select * from (select a1.code as 's1', f1.flight_number as 'first_flight',
           a2.code as 's2', f1.flight_number as 'second_flight',
           a2.code as 's3', f1.flight_number as 'third_flight',
           a2.code as 's4', f1.price as 'price', 0 as 'transition',
           f1.departure_date as departure_date, f1.arrival_date as arrival_date,
           TIMEDIFF( SUBTIME(f1.arrival_date , a2.timezone),
             SUBTIME(f1.departure_date, a1.timezone)) as 'flight_time',
           '00:00:00' as 'transition_time'
    from Flight as f1, Airport_zone as a1, Airport_zone as a2
    where f1.departure = '$departure' and f1.destination = '$destination' and
          a1.code = f1.departure and a2.code = f1.destination
  UNION
    select a1.code as 's1', f1.flight_number as 'first_flight',
           a2.code as 's2', f2.flight_number as 'second_flight',
           a3.code as 's3', f2.flight_number as 'third_flight',
           a3.code as 's4', FLOOR(0.9 * (f1.price + f2.price)) as 'price', 1 as 'transition',
           concat(f1.departure_date, '<br>', f2.departure_date) as departure_date,
           concat(f1.arrival_date, '<br>', f2.arrival_date) as arrival_date,
           ADDTIME(
             TIMEDIFF( SUBTIME(f1.arrival_date , a2.timezone),
               SUBTIME(f1.departure_date, a1.timezone)),
             TIMEDIFF( SUBTIME(f2.arrival_date , a3.timezone),
               SUBTIME(f2.departure_date, a2.timezone))
           ) as 'flight_time',
           TIMEDIFF( f2.departure_date, f1.arrival_date ) as 'transition_time'
    from Flight  as f1, Flight  as f2,
         Airport_zone as a1, Airport_zone as a2, Airport_zone as a3
    where f1.departure = '$departure' and f2.destination = '$destination' and 
          f1.destination = f2.departure and 
          a1.code = f1.departure and a2.code = f2.departure and a3.code = f2.destination and
          TIMEDIFF( f2.departure_date, f1.arrival_date ) >= '02:00:00'
  UNION
    select a1.code as 's1', f1.flight_number as 'first_flight',
           a2.code as 's2', f2.flight_number as 'second_flight',
           a3.code as 's3', f3.flight_number as 'third_flight',
           a4.code as 's4', FLOOR(0.8 * (f1.price + f2.price + f3.price)) as 'price', 2 as 'transition',
           concat(f1.departure_date, '<br>', f2.departure_date, '<br>', f3.departure_date) as departure_date,
           concat(f1.arrival_date, '<br>', f2.arrival_date, '<br>', f3.arrival_date) as arrival_date,
           ADDTIME( ADDTIME(
             TIMEDIFF( SUBTIME(f1.arrival_date , a2.timezone ),
               SUBTIME(f1.departure_date, a1.timezone )),
             TIMEDIFF( SUBTIME(f2.arrival_date , a3.timezone ),
               SUBTIME(f2.departure_date, a2.timezone ))),
             TIMEDIFF( SUBTIME(f3.arrival_date , a4.timezone ),
               SUBTIME(f3.departure_date, a3.timezone ))) as 'flight_time',
           ADDTIME(
             TIMEDIFF( f2.departure_date, f1.arrival_date ),
             TIMEDIFF( f3.departure_date, f2.arrival_date )
           ) as 'transition_time'
    from Flight  as f1, Flight  as f2, Flight  as f3,
         Airport_zone as a1, Airport_zone as a2, Airport_zone as a3, Airport_zone as a4 
    where f1.departure = '$departure' and f3.destination = '$destination' and
          f1.destination = f2.departure and
          f2.destination = f3.departure and
          a3.code != f2.arrival and
          a1.code = f1.departure and a2.code = f2.departure and
          a3.code = f3.departure and a4.code = f3.destination and
          TIMEDIFF( f2.departure_date, f1.arrival_date ) >= '02:00:00' and
          TIMEDIFF( f3.departure_date, f2.arrival_date ) >= '02:00:00'
    ) as T1
    ORDER BY $ordered_by $ordered_how";
}
?>
