# SocialHive README

## Description
SocialHive is a social media platform developed using PHP and MySQL. It provides a comprehensive set of features for users to connect, share content, and engage in online communities. The platform aims to create a vibrant and interactive space where users can interact with each other and build meaningful connections.

## Target Audience
SocialHive is designed for individuals who enjoy social networking and want to connect with others who share similar interests. It caters to a diverse audience, including people of all ages and backgrounds who are looking for a platform to share their thoughts, ideas, and experiences with others.

## Features
### User Authentication
- Sign up: Users can create a new account by providing basic information such as username, email, and password.
- Log in: Registered users can log in to their accounts using their credentials.
- Log out: Users can securely log out of their accounts to protect their privacy.

### Profile Management
- Edit profile: Users can update their profile information, including their username, email, bio, and profile picture.
- Update settings: Users can customize their account settings, such as privacy settings, notification preferences, and email notifications.

### Content Posting
- Create posts: Users can create new posts, share their thoughts, and express themselves through text and multimedia content.
- Upload images: Users can upload images and attach them to their posts to share photos and visual content with their followers.

### Group Creation
- Create groups: Users can create new groups based on specific topics, interests, or communities.
- Add members: Group admins can invite other users to join their groups and participate in group discussions and activities.

### Messaging System
- Send messages: Users can send private messages to other users and engage in one-on-one conversations.
- Group messaging: Users can send messages to groups they're a part of, participate in group chats, and collaborate with other group members.

### Follow System
- Follow users: Users can follow other users to stay updated on their activities, posts, and contributions to the platform.
- Receive notifications: Users receive notifications when someone follows them, likes their posts, comments on their posts, or mentions them in a post.

## File Structure
- The `add_members.php` file handles the addition of new members to groups.
- The `add_members_process.php` file processes the addition of members to groups.
- The `create_group.php` file handles the creation of new groups.
- The `create_group_form.php` file displays the form for creating new groups.
- The `delete_message.php` file handles the deletion of messages.
- The `edit_message.php` file handles the editing of messages.
- The `follow_handler.php` file handles follow requests between users.
- The `follow_users.php` file allows users to follow other users.
- The `group.php` file displays information about specific groups and their members.
- The `group_members.php` file manages the members of groups.
- The `group_messages.php` file manages the messages exchanged within groups.
- The `home.php` file is the home page of the platform, displaying recent posts and activities.
- The `image_buttons.php` file handles image-related actions, such as uploading and displaying images.
- The `index.php` file is the application's entry point.
- The `load_messages.php` file loads messages for display in chat interfaces.
- The `login.php` file displays the login form and handles user authentication.
- The `logout.php` file logs users out of their accounts.
- The `messages.php` file manages private messages between users.
- The `post_handler.php` file handles actions related to posting content, such as creating, editing, and deleting posts.
- The `posts.php` file manages posts and displays them on user profiles and feeds.
- The `profile.php` file displays user profiles, including profile information, posts, and followers.
- The `save_group_info.php` file saves information about groups, such as their name, description, and settings.
- The `send_group_message.php` file sends messages to groups and updates group message logs.
- The `send_group_messages.php` file manages the sending and receiving of messages within groups.
- The `send_message.php` file sends private messages between users.
- The `settings.php` file allows users to manage their account settings and preferences.
- The `signup.php` file displays the signup form and handles user registration.
- The `update_notification.php` file updates user notifications based on their preferences and activities.
- The `update_settings.php` file updates user settings and preferences.
- The `view_image.php` file displays images uploaded by users.
- The `view_post.php` file displays individual posts and their details.
- The `delete_comment.php` file deletes comments on posts.
- The `display_posts.php` file displays posts on user profiles and feed pages.
- The `edit_comment.php` file allows users to edit their comments on posts.
- The `get_preference.php` file retrieves user preferences and settings.
- The `save_preference.php` file saves user preferences and settings.
- The `user_id.php` file retrieves the user ID based on their username or email.
- The `error.js` file contains JavaScript functions for error handling.
- The `script.js` file contains general-purpose JavaScript functions used throughout the platform.
- The `settings.js` file contains JavaScript functions for managing user settings and preferences.

## Features

- **User Authentication**: Sign up, log in, and log out securely.
- **Profile Management**: Edit profile information and update account settings.
- **Content Posting**: Create posts and upload images to share thoughts and experiences.
- **Group Creation**: Create groups based on specific topics or interests and add members.
- **Messaging System**: Send private messages to other users and participate in group chats.
- **Follow System**: Follow other users and receive notifications for their activities.


## Documentation Process

SocialHive project documentation plays a crucial role in ensuring clarity, maintainability, and ease of collaboration among developers. The documentation process involves the following steps:

1. **Code Comments**: All PHP files include inline comments to explain the purpose of functions, classes, and complex logic. These comments follow a consistent format and style for easy readability.

2. **README.md**: This README file serves as the primary source of project documentation. It provides an overview of the project, including its description, features, usage instructions, and file structure. Additionally, it outlines the project requirements and specifications, along with instructions for setting up the development environment and deploying the application.

3. **Code Documentation**: Apart from inline comments, the project aims to maintain comprehensive code documentation using tools like PHPDoc. This documentation includes descriptions of functions, parameters, return types, and usage examples to facilitate understanding and usage for other developers.

4. **Version Control Commits**: Each commit made to the GitHub repository includes a descriptive message summarizing the changes made in that commit. These messages follow a consistent format and provide context for the modifications, making it easier to track changes over time.



## Usage
1. Clone the repository to your local machine using `git clone`.
2. Set up a web server environment with PHP and MySQL.
3. Import the provided database schema (`database_schema.sql`) into your MySQL database.
4. Configure the database connection settings in the `connection.php` file.
5. Access the application through your web server's URL.
6. Sign up for a new account or use the provided login credentials to access the platform.
7. Explore the various features and functionalities of the platform.

## Credits
This project was developed by Elisea Mizzi
