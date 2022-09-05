create table customer_meta(
    id int(10) not null primary key auto_increment,
    points int(11),
    topup_amount decimal(10,2),
    customer_id int(10) not null,
    updated_at timestamp default now() ON UPDATE now()
);