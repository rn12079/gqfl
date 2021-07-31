create table company (
id int not null primary key auto_increment,
name varchar(200) );

create table suppliers (
id int not null primary key auto_increment,
name varchar(200),
contactno int(15),
address varchar(200),
contactref varchar(200) )
;

insert into suppliers(name) select distinct supplier from products;

create table comp_prods (
    comp_id int, 
    prod_id int,
    primary key(comp_id,prod_id), 
    foreign key (comp_id) references company(id), 
    foreign key (prod_id) references products(id)
    );

create table prod_sup ( 
    prod_id int not null, 
    sup_id int not null, 
    primary key (prod_id,sup_id), 
    foreign key (prod_id) references products(id), 
    foreign key (sup_id) references suppliers(id) 
    );

insert into prod_sup(prod_id,sup_id) 
    select p.id,s.id from products p join suppliers s on p.supplier=s.name;

alter table inventory add column sup_id int;

alter table inventory add column supplier_name varchar(200);

alter table inventory add foreign key (sup_id) references suppliers(id);

update inventory i,(select s.id s_id,p.id p_id from inventory i join products p on i.product_id=p.id  join suppliers s on p.supplier=s.name
) j 
set i.sup_id=j.s_id 
where i.product_id=j.p_id;

alter table products modify supplier varchar(255);

alter table suppliers modify name varchar(200) not null;

alter table suppliers modify contactno long;

alter table locations add column comp_id int;

alter table locations add foreign key (comp_id) references company(id);

update locations set comp_id=1 where type="store";

alter table inventory add column comp_id int;

alter table inventory add foreign key (comp_id) references company(id);

alter table inventory add column loc_id int;

alter table inventory add foreign key  (loc_id) references locations(id);

update inventory set comp_id=1 where 1=1;

update inventory i,(select l.id,l.name from locations l ) j set i.loc_id=j.id where i.receiver=j.name;

alter table inventory modify receiver varchar(255);


select i.id,p.id,p.product_name,cases,namount,tad,taxrate,tax,amount from 
        inventory i join products p on i.product_id=p.id 
        where
            date = '2021-03-02' and 
            comp_id=1 and
            sup_id=26 and
            loc_id=1 and
            invoice_ref='11'
            "


update inventory set product_id=104 where product_id=106;
update products set maker='generic',supplier=null  where id in (103,104)

delete from comp_prods where prod_id in (160,162,106,261,159);
update prod_sup set prod_id = '103' where prod_id in (160,162,261);
delete from products where id in (160,162,106,261,159);








delete from inventory where product_id in (273,274,275,280);
Query OK, 5 rows affected (0.01 sec)

mysql> delete from prod_sup where prod_id in (273,274,275,280);
Query OK, 4 rows affected (0.00 sec)

mysql> delete from comp_prods where prod_id in (273,274,275,280);
Query OK, 4 rows affected (0.00 sec)

mysql> delete from products where id in (273,274,275,280);
Query OK, 4 rows affected (0.00 sec)



