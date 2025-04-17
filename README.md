# SocialHive README

## Description
Welcome to SocialHive! It's a social media platform built with PHP and MySQL, designed to help people connect, share their stories, and build communities online. Whether you're looking to share your thoughts, discover new content, or connect with others who share your passions, SocialHive offers a space to make it happen.

## Who's it for?
SocialHive is for anyone who loves social networking and wants to meet like-minded people. It's perfect for people of all ages who want to connect, chat, and share ideas, whether you're into hobbies, topics of interest, or just looking for a place to engage with others.

## What you can do on SocialHive

### User Authentication
- **Sign up**: Creating an account is simple and quick—just add your username, email, and password.
- **Log in**: Once you're registered, logging in gives you full access to the platform.
- **Log out**: You can always log out to keep your account safe and secure.

### Manage Your Profile
- **Edit profile**: Personalize your profile by updating your username, bio, email, or profile picture.
- **Update settings**: Adjust your account settings like privacy, notifications, and more to make your experience just right.

### Share Your Thoughts
- **Create posts**: Share your thoughts, ideas, and stories with the community through text, images, and more.
- **Upload images**: Want to share pictures too? You can upload images to make your posts more engaging.

### Create and Join Groups
- **Create groups**: Build groups around the topics or interests that matter to you. Whether it's a hobby, a cause, or just a fun community, it’s easy to get started.
- **Invite members**: As a group admin, you can invite others to join your group and start engaging.

### Messaging
- **Send messages**: Start private conversations with anyone on the platform—chat with friends or new connections.
- **Group chats**: In groups, you can send messages to multiple people at once, keeping the conversation flowing.

### Follow System
- **Follow others**: Stay updated on the posts and activities of people you find interesting.
- **Get notified**: Receive notifications when someone follows you, likes your posts, or comments on your content.

### Admin Panel (For Admins)
- **Manage users**: Admins can control user accounts by blocking/unblocking or deleting users when necessary.
- **Post management**: Admins can monitor posts and remove inappropriate content if needed.
- **Moderate comments**: Admins can delete comments that don’t follow the community guidelines.

## File Structure

- **includes/**
  - `header.php`: Contains the site’s header and navigation.
  - `dbfunctions.php`: Functions for interacting with the database.
  - `connection.php`: Manages the connection to the MySQL database.

- **pages/**: The core files for the platform’s functionality.
  - `add_members.php`: Add new members to groups.
  - `create_group.php`: Create new groups.
  - `follow_handler.php`: Handle follow requests between users.
  - `group.php`: Displays group details and members.
  - `home.php`: The home page, showing posts and recent activities.
  - `index.php`: The main entry point of the app.
  - `login.php`: The login page.
  - `logout.php`: Logs users out of their account.
  - `messages.php`: Manages private messaging.
  - `post_handler.php`: Handles creating, editing, and deleting posts.
  - `profile.php`: Displays user profiles.
  - **admin/** (For Admins)
    - `admin_dashboard.php`: The admin dashboard with an overview of site activity.
    - `user_management.php`: Admins can manage users here (block/unblock, delete users).
    - `post_management.php`: Admins can review and remove posts.
    - `comment_management.php`: Admins can manage comments for posts.

- **js/**: JavaScript files to enhance functionality.
  - `script.js`: General JavaScript functions used across the site.
  - `settings.js`: Handles the management of user settings and preferences.

## Documentation Process

### Features
- **User Authentication**: Easy sign up, log in, and log out for secure access.
- **Profile Management**: Personalize your account and manage settings.
- **Content Creation**: Share posts and images with your followers.
- **Groups**: Create or join groups based on shared interests.
- **Messaging**: Chat privately or in groups.
- **Follow System**: Follow users and receive notifications about their activities.
- **Admin Panel**: Admins can manage users, posts, and comments.

### How We Document
1. **Code Comments**: I’ve included comments in the code to make it easier to follow for other developers. Each function is clearly explained.
2. **README.md**: This document serves as the go-to guide for understanding the project, how to use it, and where everything is located.
3. **Code Documentation**: I use tools like PHPDoc to document the code in detail, so you can easily understand the purpose and usage of functions and classes.
4. **Version Control**: I keep track of all changes with descriptive commit messages on GitHub, so you can see the history of the project.

## How to Use SocialHive

1. Clone the repository to your local machine using `git clone`.
2. Set up a PHP and MySQL environment on your web server.
3. Import the database schema (`database_schema.sql`) into MySQL.
4. Update the database connection settings in `connection.php`.
5. Visit the application in your browser and sign up or log in.
6. Explore the features and start connecting with others!

## Credits
This project was developed by Elisea Mizzi.
