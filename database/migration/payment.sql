drop table if exists payments;
create table payments(
    id int(10) not null primary key auto_increment,
    reference varchar(50),
    customer_id int(10),
    parent_id int(10),
    parent_type varchar(50),
    amount decimal(10,2),
    payment_method varchar(25),
    payment_reference varchar(25),
    approval_status enum('pending','approved','declined'),
    approval_date datetime default null ON UPDATE now(),
    approval_by int(10) default null,
    created_by int(10),
    created_at timestamp default now() ON UPDATE now()
);

/*
*parent_type (transaction,common,etc),
*payment_method (gcash,cash,top_up)
*/