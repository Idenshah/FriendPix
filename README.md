# FriendPix

FriendPix is a multi-page, responsive web application inspired by early versions of social media platforms. Built with **PHP**, **Bootstrap**, **JavaScript**, and **CSS3**, the application allows users to create photo albums, manage privacy settings, interact with friends, and share comments on photos.

## Features

- **User Authentication**:  
  Welcome page with options to sign up or log in, with session management for temporary user data storage.

- **Photo Albums**:  
  Create albums that can be private or public (accessible to friends).

- **Image Management**:  
  Upload photos to albums and organize them based on privacy settings.

- **Friendship Invitations**:  
  Send and respond to friendship requests.

- **Photo Comments**:  
  Friends can comment on photos in albums set as public-with-friends by the album owner.

- **Responsive Design**:  
  Fully responsive across different browsers, leveraging **Bootstrap** for modern, adaptive layouts.

- **SSL Integration**:  
  Deployed with an SSL certificate provided by Google Trust for secure data exchange.

## Technology Stack

- **Frontend**:  
  Bootstrap, JavaScript, CSS3

- **Backend**:  
  PHP

- **Database**:  
  MySQL, managed via PHPMyAdmin

- **Deployment**:  
  SSL setup for secure access

## Application Structure

```bash
.
├── index.php             # Welcome page with user login/signup
├── signUp.php            # User registration form
├── login.php             # User login page
├── logOut.php            # User logout page
├── AddAlbums.php         # Create and manage photo albums
├── MyAlbums.php          # Manage Albums
├── MyPictures.php        # Manage Images and Comments
├── UploadPictures.php    # Upload images to albums
├── MyFriends.php         # Manage friendship invitations
├── AddFriends.php        # Send a friendship invitation
├── FriendsPictures.php   # View and leave comments on public albums
├── Validation.php        # Manage session and patterns
├── assets/
│   ├── css/              # Custom CSS files
│   ├── js/               # JavaScript files
└── DataSource.ini/
    └── friendpix.sql     # Database schema
