-- Table: Users
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Categories
CREATE TABLE Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Table: Media
CREATE TABLE Media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('image', 'video', 'gif') NOT NULL,
    url VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES Categories(id) ON DELETE CASCADE
);

-- Table: Tags
CREATE TABLE Tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Table: Media_Tags
CREATE TABLE Media_Tags (
    media_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (media_id, tag_id),
    FOREIGN KEY (media_id) REFERENCES Media(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES Tags(id) ON DELETE CASCADE
);

-- Table: Achievements
CREATE TABLE Achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: Roles
CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) NOT NULL UNIQUE
);

-- Table: Permissions
CREATE TABLE Permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission VARCHAR(50) NOT NULL UNIQUE
);

-- Table: Role_Permissions
CREATE TABLE Role_Permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES Roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES Permissions(id) ON DELETE CASCADE
);

-- Table: User_Roles
CREATE TABLE User_Roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES Roles(id) ON DELETE CASCADE
);

-- Table: Subscriptions
CREATE TABLE Subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan ENUM('basic', 'premium') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: Reports
CREATE TABLE Reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_id INT NOT NULL,
    reported_by INT NOT NULL,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (media_id) REFERENCES Media(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: User_Groups
CREATE TABLE User_Groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Table: Group_Media
CREATE TABLE Group_Media (
    group_id INT NOT NULL,
    media_id INT NOT NULL,
    PRIMARY KEY (group_id, media_id),
    FOREIGN KEY (group_id) REFERENCES User_Groups(id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES Media(id) ON DELETE CASCADE
);

-- Table: Group_Users
CREATE TABLE Group_Users (
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (group_id, user_id),
    FOREIGN KEY (group_id) REFERENCES User_Groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: Comments
CREATE TABLE Comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (media_id) REFERENCES Media(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: Likes
CREATE TABLE Likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (media_id) REFERENCES Media(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Indexes
ALTER TABLE Media ADD INDEX idx_user_id (user_id);
ALTER TABLE Media ADD INDEX idx_category_id (category_id);
ALTER TABLE Media ADD INDEX idx_type (type);
ALTER TABLE Media ADD INDEX idx_created_at (created_at);

ALTER TABLE Achievements ADD INDEX idx_user_id (user_id);

ALTER TABLE Subscriptions ADD INDEX idx_user_id (user_id);
ALTER TABLE Subscriptions ADD INDEX idx_plan (plan);

ALTER TABLE Reports ADD INDEX idx_media_id (media_id);
ALTER TABLE Reports ADD INDEX idx_reported_by (reported_by);

ALTER TABLE Group_Media ADD INDEX idx_group_id (group_id);
ALTER TABLE Group_Media ADD INDEX idx_media_id (media_id);

ALTER TABLE Group_Users ADD INDEX idx_group_id (group_id);
ALTER TABLE Group_Users ADD INDEX idx_user_id (user_id);

ALTER TABLE Comments ADD INDEX idx_media_id (media_id);
ALTER TABLE Comments ADD INDEX idx_user_id (user_id);

ALTER TABLE Likes ADD INDEX idx_media_id (media_id);
ALTER TABLE Likes ADD INDEX idx_user_id (user_id);