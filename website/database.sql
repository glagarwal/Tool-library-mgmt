Create table Users(
    email_ID VARCHAR(20) PRIMARY KEY,
    pass VARCHAR(512),
    role VARCHAR(10)
);

Create table Admin(
    email_ID VARCHAR(20) PRIMARY KEY,
    admin_ID CHAR(10) UNIQUE NOT NULL,
    admin_name VARCHAR(25),
    FOREIGN KEY(email_ID) REFERENCES User(email_ID)
);

Create table Student(
    email_ID VARCHAR(20) PRIMARY KEY,
    f_name VARCHAR(15),
    l_name VARCHAR(15),
    uga_ID CHAR(10) UNIQUE NOT NULL,
    phone CHAR(10),
    department VARCHAR(20),
    total_fine INT,
    FOREIGN KEY(email_ID) REFERENCES User(email_ID)
);

Create table Location(
    loc_ID INT PRIMARY KEY,
    bldg_name VARCHAR(30),
    room TINYINT(10)
);

Create table Inventory(
    item_ID INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(30),
    category VARCHAR(20),
    description VARCHAR(500),
    cost INT,
    date_introduced DATE,
    location_ID INT REFERENCES Location(loc_ID),
    availability CHAR(1) DEFAULT 'Y'
);

Create table Borrows(
    stud_id VARCHAR(20) REFERENCES Student(email_ID),
    item_ID INT REFERENCES Inventory(item_ID),
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date TIMESTAMP AS (issue_date + INTERVAL 7 DAY),
    return_date TIMESTAMP,
    comments VARCHAR(100),
    fine INT,
    PRIMARY KEY(stud_id, item_ID, issue_date)
);

Create table Item_review(
    stud_id VARCHAR(20) REFERENCES Student(email_ID),
    item_ID INT REFERENCES Inventory(item_ID),
    rating TINYINT(1) NOT NULL,
    review VARCHAR(1000),
    CONSTRAINT chk_rating Check(rating <= 5)
);