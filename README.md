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
- **includes/**
  - `header.php`: Contains the header HTML and navigation menu.
  - `dbfunctions.php`: Contains database-related functions.
  - `connection.php`: Manages the connection to the MySQL database.

- **pages/**: Contains most of the main code files.
  - `add_members.php`: Handles the addition of new members to groups.
  - `add_members_process.php`: Processes the addition of members to groups.
  - `create_group.php`: Handles the creation of new groups.
  - `create_group_form.php`: Displays the form for creating new groups.
  - `delete_message.php`: Handles the deletion of messages.
  - `edit_message.php`: Handles the editing of messages.
  - `follow_handler.php`: Handles follow requests between users.
  - `follow_users.php`: Allows users to follow other users.
  - `group.php`: Displays information about specific groups and their members.
  - `group_members.php`: Manages the members of groups.
  - `group_messages.php`: Manages the messages exchanged within groups.
  - `home.php`: The home page of the platform, displaying recent posts and activities.
  - `image_buttons.php`: Handles image-related actions, such as uploading and displaying images.
  - `index.php`: The application's entry point.
  - `load_messages.php`: Loads messages for display in chat interfaces.
  - `login.php`: Displays the login form and handles user authentication.
  - `logout.php`: Logs users out of their accounts.
  - `messages.php`: Manages private messages between users.
  - `post_handler.php`: Handles actions related to posting content, such as creating, editing, and deleting posts.
  - `posts.php`: Manages posts and displays them on user profiles and feeds.
  - `profile.php`: Displays user profiles, including profile information, posts, and followers.
  - `save_group_info.php`: Saves information about groups, such as their name, description, and settings.
  - `send_group_message.php`: Sends messages to groups and updates group message logs.
  - `send_group_messages.php`: Manages the sending and receiving of messages within groups.
  - `send_message.php`: Sends private messages between users.
  - `settings.php`: Allows users to manage their account settings and preferences.
  - `signup.php`: Displays the signup form and handles user registration.
  - `update_notification.php`: Updates user notifications based on their preferences and activities.
  - `update_settings.php`: Updates user settings and preferences.
  - `view_image.php`: Displays images uploaded by users.
  - `view_post.php`: Displays individual posts and their details.
  - `delete_comment.php`: Deletes comments on posts.
  - `display_posts.php`: Displays posts on user profiles and feed pages.
  - `edit_comment.php`: Allows users to edit their comments on posts.
  - `get_preference.php`: Retrieves user preferences and settings.
  - `save_preference.php`: Saves user preferences and settings.
  - `user_id.php`: Retrieves the user ID based on their username or email.

- **js/**: Contains files placed here for better detection.
  - `error.js`: Contains JavaScript functions for error handling.
  - `script.js`: Contains general-purpose JavaScript functions used throughout the platform.
  - `settings.js`: Contains JavaScript functions for managing user settings and preferences.

## Documentation Process
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
