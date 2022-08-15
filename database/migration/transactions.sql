creat table transaction(
    id int(10) not null primary key auto_increment,
    action_taken varchar(100)
);

/*
*action-taken
*[pick_up,available,deliveries,pickups,payments]
*/


create table deliveries();
create table pickups();
create table delivered();
create table pending_payments();