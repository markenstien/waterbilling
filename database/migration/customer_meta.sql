create table customer_meta(
    id int(10) not null primary key auto_increment,
    points decimal(10,2),
    topup_amount decimal(10,2),
    user_id int(10) not null,
    updated_at timestamp decimal now() ON UPDATE now()
);