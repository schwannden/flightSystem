    select a1.code as 's1', f1.flight_number as 'first_flight',
            a2.code as 's2', f1.flight_number as 'second_flight',
            a2.code as 's3', f1.flight_number as 'third_flight',
            a2.code as 's4', f1.price as 'price', 0 as 'transition',
           TIMEDIFF( ADDTIME(f1.arrival_date , -a2.timezone),
             ADDTIME(f1.departure_date, -a1.timezone)) as 'travel_time',
           '00:00:00' as 'transition_time'
    from Flight as f1, Airport_zone as a1, Airport_zone as a2
    where f1.departure = 'TPE' and f1.destination = 'DSM' and
          a1.code = f1.departure and a2.code = f1.destination
  UNION
    select a1.code as 's1', f1.flight_number as 'first_flight',
           a2.code as 's2', f2.flight_number as 'second_flight',
           a3.code as 's3', f2.flight_number as 'third_flight',
           a3.code as 's4', f1.price + f2.price as 'price', 1 as 'transition',
           ADDTIME(
             TIMEDIFF( ADDTIME(f1.arrival_date , -a2.timezone),
               ADDTIME(f1.departure_date, -a1.timezone)),
             TIMEDIFF( ADDTIME(f2.arrival_date , -a3.timezone),
               ADDTIME(f2.departure_date, -a2.timezone))
           ) as 'travel_time',
           TIMEDIFF( f2.departure_date, f1.arrival_date ) as 'transition_time'
    from Flight  as f1, Flight  as f2,
         Airport_zone as a1, Airport_zone as a2, Airport_zone as a3
    where f1.departure = 'TPE' and f2.destination = 'DSM' and 
          f1.destination = f2.departure and 
          a1.code = f1.departure and a2.code = f2.departure and a3.code = f2.destination and
          TIMEDIFF( f2.departure_date, f1.arrival_date ) > '2:00'
  UNION
    select a1.code as 's1', f1.flight_number as 'first_flight',
           a2.code as 's2', f2.flight_number as 'second_flight',
           a3.code as 's3', f3.flight_number as 'third_flight',
           a4.code as 's4', f1.price + f2.price + f3.price as 'price', 2 as 'transition',
           ADDTIME( ADDTIME(
             TIMEDIFF( ADDTIME(f1.arrival_date , -a2.timezone ),
               ADDTIME(f1.departure_date, -a1.timezone )),
             TIMEDIFF( ADDTIME(f2.arrival_date , -a3.timezone ),
               ADDTIME(f2.departure_date, -a2.timezone ))),
             TIMEDIFF( ADDTIME(f3.arrival_date , -a4.timezone ),
               ADDTIME(f3.departure_date, -a3.timezone ))) as 'travel_time',
           ADDTIME(
             TIMEDIFF( f2.departure_date, f1.arrival_date ),
             TIMEDIFF( f3.departure_date, f2.arrival_date )
           ) as 'transition_time'
    from Flight  as f1, Flight  as f2, Flight  as f3,
         Airport_zone as a1, Airport_zone as a2, Airport_zone as a3, Airport_zone as a4 
    where f1.departure = 'TPE' and f3.destination = 'DSM' and
          f1.destination = f2.departure and
          f2.destination = f3.departure and
          a1.code = f1.departure and a2.code = f2.departure and
          a3.code = f3.departure and a4.code = f3.destination and
          TIMEDIFF( f2.departure_date, f1.arrival_date ) > '2:00' and
          TIMEDIFF( f3.departure_date, f2.arrival_date ) > '2:00';