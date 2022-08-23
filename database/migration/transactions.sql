drop table if exists transactions;
create table transactions(
    id int(10) not null primary key auto_increment,
    user_id int(10),
    platform_id int(10),
    container_id int(10),
    customer_id int(10),
    parent_id int(10),
    parent_key varchar(50),
    amount decimal(10,2),
    created_at timestamp default now() ON UPDATE now() 
);

/*
*action-taken
*[pick_up,available,deliveries,pickups,payments]
*/


create table deliveries();
create table pickups();
create table delivered();
create table pending_payments();