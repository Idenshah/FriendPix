# FriendPix

FriendPix is a multi-page, responsive web application inspired by early versions of social media platforms. Built with **PHP**, **Bootstrap**, **JavaScript**, and **CSS3**, the application allows users to create photo albums, manage privacy settings, interact with friends, and share comments on photos.

---

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

---

## Technology Stack

- **Frontend**: Bootstrap, JavaScript, CSS3
- **Backend**: PHP
- **Database**: MySQL, managed via PHPMyAdmin
- **Deployment**: SSL setup for secure access

---

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
└── database/
    └── friendpix.sql     # Database schema
```
## Installation

### Prerequisites
- **PHP** >= 7.4  
- **MySQL**  
- A web server (**Apache**/**Nginx**)  
- **Composer** (optional, for dependency management)  


### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Idenshah/FriendPix.git
   cd FriendPix
2. **Set up the Database**:  

- **Import the database**:  
   Import the `friendpix.sql` file located in the `database/` folder using **PHPMyAdmin** or **MySQL CLI**.

- **Update database credentials**:  
   Update the database credentials in the `config.php` file to match your environment.

---

## Deploy the Application

1. **Place the files in the web server's root directory**:  
   Ensure all application files are deployed to the correct root directory of your web server.

2. **Set up SSL support**:  
   Make sure the server supports SSL. (Google Trust SSL certificate setup is included.)

3. **Access the application**:  
   Use your browser to navigate to:
   ```bash
   http://yourdomain.com
   ```
   ## Usage

1. Navigate to the homepage.  
2. Sign up or log in to create a session.  
3. Create albums (private or public).  
4. Upload photos and manage privacy settings.  
5. Send and respond to friendship invitations.  
6. Share comments on public albums with friends.

---

## Contributing

Contributions are welcome! Follow these steps to contribute:

1. **Fork the repository**.  
2. **Create a new branch**:
   ```bash
   git checkout -b feature-name
   ```
3. **Commit your changes**:
  ```bash
  git commit -m "Add feature name"
   ```
4. **Push to your branch**:
  ```bash
git push origin feature-name
   ```
5. **Open a pull request.**:

## License

This project is licensed under the **MIT License**.  
See the [LICENSE](LICENSE) file for more details.

---

## Acknowledgments

- **Google Trust SSL** for certificate services.  
- **Bootstrap** for making responsive design effortless.  
- Early social media platforms for inspiring this project.


