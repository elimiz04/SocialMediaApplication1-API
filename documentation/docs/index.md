# API Documentation for SocialHive

Welcome to the API documentation for **SocialHive**, a PHP and MySQL-based social media platform. This guide is intended for developers who want to integrate with or understand how to use the SocialHive API.

---

## Users

### `POST /api/users/create_user.php`

**Description:** Create a new user account.

* **Request Body:** JSON

  ```json
  {
    "username": "john_doe",
    "email": "john@example.com",
    "password": "securePassword123"
  }
  ```
* **Responses:**

  * `201 Created`: User successfully created
  * `409 Conflict`: Username already exists

### `GET /api/users/get_user.php?id=1`

**Description:** Fetch a user by ID.

* **Response:**

  ```json
  {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com"
  }
  ```

### `POST /api/users/login.php`

**Description:** Authenticate a user and return user data.

### `DELETE /api/users/delete_user.php`

**Description:** Delete a user by ID.

* **Request Body:** `{ "user_id": 1 }`

---

## Posts

### `POST /api/posts/create_post.php`

**Description:** Create a new post.

* **Request Body:**

  ```json
  {
    "user_id": 1,
    "content": "Hello World!",
    "image": "optional.jpg"
  }
  ```

### `GET /api/posts/get_posts.php`

**Description:** Get all posts ordered by `created_at`.

### `DELETE /api/posts/delete_post.php`

**Description:** Delete a specific post by ID.

---

## Comments

### `POST /api/comments/create_comment.php`

**Description:** Add a comment to a post.

### `GET /api/comments/get_comments_by_post.php?post_id=1`

**Description:** Get all comments for a post.

### `POST /api/comments/update_comment.php`

**Description:** Update an existing comment.

---

## Likes

### `POST /api/likes/like_post.php`

**Description:** Like or update like status on a post.

### `GET /api/likes/get_liked_posts_by_user.php?user_id=1`

**Description:** Fetch all posts liked by a specific user.

### `GET /api/likes/count_likes.php?post_id=1`

**Description:** Get the number of likes for a post.

---

## Follows

### `POST /api/follows/follow_user.php`

**Description:** Follow or unfollow a user.

* **Request Body:**

  ```json
  {
    "follower_id": 1,
    "followed_id": 2,
    "action": "follow"
  }
  ```

### `GET /api/follows/get_followers_by_user.php?user_id=2`

**Description:** Get followers of a user.

### `GET /api/follows/get_following_by_user.php?user_id=2`

**Description:** Get users followed by a specific user.

---

## Messages

### `POST /api/messages/send_message.php`

**Description:** Send a private message to another user.

### `POST /api/messages/mark_as_read.php`

**Description:** Mark messages as read.

---

## Notifications

### `GET /api/notifications/get_notifications.php?user_id=1`

**Description:** Get all notifications for a user.

### `POST /api/notifications/mark_as_read.php`

**Description:** Mark notifications as read for a user.

---

## Groups

### `POST /api/groups/create_group.php`

**Description:** Create a new group.

### `POST /api/groups/add_member.php`

**Description:** Add user to a group.

### `GET /api/groups/get_group_members.php?group_id=1`

**Description:** List all members in a group.

---

## 3rd Party API

### Official Joke API

**Endpoint Used:** `https://official-joke-api.appspot.com/random_joke`

* **Usage:** Enhances user experience by displaying a light-hearted joke on the homepage.
* **cURL Example:**

  ```php
  $curl = curl_init("https://official-joke-api.appspot.com/random_joke");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  ```
