-- Example Data for Users
INSERT INTO Users (username, email, password_hash) VALUES 
('john_doe', 'john@example.com', '$2y$10$hashedpassword1'),
('jane_smith', 'jane@example.com', '$2y$10$hashedpassword2'),
('alice_jones', 'alice@example.com', '$2y$10$hashedpassword3');

-- Example Data for Categories
INSERT INTO Categories (name) VALUES 
('Nature'),
('Technology'),
('Animals'),
('Art');

-- Example Data for Tags
INSERT INTO Tags (name) VALUES 
('sunset'),
('ocean'),
('gadgets'),
('cats'),
('funny'),
('abstract');

-- Example Data for Media
INSERT INTO Media (title, description, type, url, user_id, category_id) VALUES 
('Beautiful Sunset', 'A stunning sunset over the ocean.', 'image', 'https://example.com/sunset.jpg', 1, 1),
('Tech Gadgets', 'Various tech gadgets.', 'image', 'https://example.com/gadgets.jpg', 2, 2),
('Funny Cat Video', 'Cats being funny.', 'video', 'https://example.com/catvideo.mp4', 3, 3),
('Abstract Art', 'Abstract art piece.', 'image', 'https://example.com/art.jpg', 1, 4);

-- Example Data for Media_Tags
INSERT INTO Media_Tags (media_id, tag_id) VALUES 
(1, 1), -- Beautiful Sunset
(1, 2), -- Beautiful Sunset
(2, 3), -- Tech Gadgets
(3, 4), -- Funny Cat Video
(3, 5), -- Funny Cat Video
(4, 6); -- Abstract Art

-- Example Data for Achievements
INSERT INTO Achievements (title, description, user_id) VALUES 
('First Upload', 'Uploaded your first media.', 1),
('Popular Media', 'Your media has been liked 10 times.', 2),
('Active User', 'Logged in 5 consecutive days.', 3);

-- Example Data for Roles
INSERT INTO Roles (role) VALUES 
('Admin'),
('Moderator'),
('User');

-- Example Data for Permissions
INSERT INTO Permissions (permission) VALUES 
('manage_media'),
('view_analytics'),
('moderate_content');

-- Example Data for Role_Permissions
INSERT INTO Role_Permissions (role_id, permission_id) VALUES 
(1, 1), -- Admin can manage media
(1, 2), -- Admin can view analytics
(2, 3), -- Moderator can moderate content
(3, 2); -- User can view analytics

-- Example Data for User_Roles
INSERT INTO User_Roles (user_id, role_id) VALUES 
(1, 1), -- John Doe is an Admin
(2, 2), -- Jane Smith is a Moderator
(3, 3); -- Alice Jones is a User

-- Example Data for Subscriptions
INSERT INTO Subscriptions (user_id, plan) VALUES 
(1, 'premium'),
(2, 'basic'),
(3, 'premium');

-- Example Data for Reports
INSERT INTO Reports (media_id, reported_by, reason) VALUES 
(2, 3, 'Inappropriate content'),
(3, 1, 'Not family-friendly');

-- Example Data for User_Groups
INSERT INTO User_Groups (name) VALUES 
('Photography Lovers'),
('Tech Enthusiasts'),
('Animal Fans');

-- Example Data for Group_Media
INSERT INTO Group_Media (group_id, media_id) VALUES 
(1, 1), -- Photography Lovers
(1, 4), -- Photography Lovers
(2, 2), -- Tech Enthusiasts
(3, 3); -- Animal Fans

-- Example Data for Group_Users
INSERT INTO Group_Users (group_id, user_id) VALUES 
(1, 1), -- John Doe in Photography Lovers
(2, 2), -- Jane Smith in Tech Enthusiasts
(3, 3); -- Alice Jones in Animal Fans

-- Example Data for Comments
INSERT INTO Comments (media_id, user_id, content) VALUES 
(1, 2, 'Great shot!'),
(2, 3, 'Cool gadgets!'),
(3, 1, 'Hilarious!');

-- Example Data for Likes
INSERT INTO Likes (media_id, user_id) VALUES 
(1, 3),
(2, 1),
(3, 2),
(4, 1);