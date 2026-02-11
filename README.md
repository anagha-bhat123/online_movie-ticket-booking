# Online Movie Ticket Booking System

A comprehensive web-based application for online movie ticket booking with admin and user dashboards.

## 📋 Table of Contents
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Database](#database)
- [Admin Features](#admin-features)
- [User Features](#user-features)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### For Users
- **User Authentication**: Registration and login with OTP verification
- **Movie Browsing**: Browse available movies with detailed information
- **Ticket Booking**: Select shows, seats, and book tickets
- **Food Ordering**: Select and order food items while booking
- **Payment Integration**: Secure payment processing via Razorpay
- **Booking Management**: View and cancel bookings
- **Receipt Generation**: Download booking receipts as PDF
- **Feedback**: Submit feedback and ratings
- **User Profile**: Manage account and view booking history

### For Admins
- **Dashboard**: Overview of bookings, users, and system statistics
- **Movie Management**: Add, edit, and manage movie details
- **Theater Management**: Manage theaters and screens
- **Show Management**: Create and manage movie shows
- **Food Management**: Manage food items and pricing
- **User Management**: View and manage user accounts
- **Booking Reports**: Generate and view booking reports
- **Feedback Management**: View customer feedback

## 🛠️ Technology Stack

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **Payment Gateway**: Razorpay
- **PDF Generation**: DOMPDF
- **Server**: Apache (or any PHP-compatible server)
- **Dependency Manager**: Composer

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- Composer installed
- Git

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/anagha-bhat123/online_movie-ticket-booking.git
   cd online_movie-ticket-booking
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup Database**
   - Create a new MySQL database
   - Import the database schema:
   ```bash
   mysql -u root -p your_database < online_movie.sql
   ```

4. **Configure Database Connection**
   - Update database credentials in `includes/db.php`:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "your_password";
   $dbname = "online_movie";
   ```

5. **Configure Razorpay Keys**
   - Add your Razorpay API keys in the payment configuration files

6. **Setup Web Server**
   - Point your Apache document root to the project directory
   - Ensure proper permissions on the project folders

7. **Access the Application**
   - User Panel: `http://localhost/online_movie/user/`
   - Admin Panel: `http://localhost/online_movie/admin/`

## 📂 Project Structure

```
online_movie/
├── admin/                    # Admin dashboard files
│   ├── dashboard.php        # Admin dashboard
│   ├── login.php            # Admin login
│   ├── manage_movies.php    # Movie management
│   ├── manage_theaters.php  # Theater management
│   ├── manage_shows.php     # Show management
│   ├── manage_food.php      # Food management
│   ├── manage_users.php     # User management
│   ├── report_booking.php   # Booking reports
│   └── view_feedback.php    # Feedback management
├── user/                     # User interface files
│   ├── index.php            # Home page
│   ├── login.php            # User login
│   ├── register.php         # User registration
│   ├── movie_detail.php     # Movie details
│   ├── book_ticket.php      # Ticket booking
│   ├── select_seat.php      # Seat selection
│   ├── select_food.php      # Food selection
│   ├── payment.php          # Payment page
│   ├── confirmation.php     # Booking confirmation
│   ├── cancel_booking.php   # Booking cancellation
│   └── receipt.php          # Receipt generation
├── includes/                 # Shared includes
│   ├── db.php              # Database connection
│   ├── header.php          # Page header
│   └── footer.php          # Page footer
├── css/                      # Stylesheets
│   └── style.css           # Main styles
├── js/                       # JavaScript files
│   └── main.js             # Main script
├── images/                   # Image assets
├── vendor/                   # Composer dependencies
├── online_movie.sql         # Database schema
└── README.md                # This file
```

## 🚀 Usage

### User Workflow
1. Register a new account or login
2. Browse available movies
3. Select a movie and show
4. Choose seats
5. Add food items (optional)
6. Proceed to payment
7. Complete payment via Razorpay
8. Download receipt
9. View booking history
10. Cancel bookings if needed

### Admin Workflow
1. Login to admin panel
2. Access dashboard for overview
3. Manage movies, theaters, shows, and food items
4. View user accounts and bookings
5. Generate booking reports
6. View and respond to feedback

## 🗄️ Database

The application uses MySQL database with the following main tables:
- `users` - User accounts
- `movies` - Movie information
- `theaters` - Theater details
- `shows` - Movie show schedules
- `seats` - Theater seat information
- `bookings` - Ticket bookings
- `food_items` - Food menu items
- `food_orders` - Food orders
- `payments` - Payment transactions
- `feedback` - User feedback

Import `online_movie.sql` to setup the database schema.

## 🔐 Authentication

- User authentication with email and OTP verification
- Admin authentication with username and password
- Session management for user and admin sessions
- Password hashing for security

## 💳 Payment Gateway

- **Razorpay Integration**: Secure payment processing
- Payment verification and confirmation
- Transaction history and receipts

## 📧 Notifications

- Email notifications for bookings
- OTP delivery to registered email
- Booking confirmation emails
- Receipt generation and download

## 📱 Responsive Design

The application is designed to be responsive and accessible on:
- Desktop browsers
- Tablets
- Mobile devices

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👤 Author

**Anagha Bhat**
- GitHub: [@anagha-bhat123](https://github.com/anagha-bhat123)

## 🐛 Bug Reports

Found a bug? Please create an issue on GitHub with:
- Description of the bug
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)

## 📞 Support

For support, please:
- Create an issue on GitHub
- Contact via email

## 🎯 Future Enhancements

- [ ] Mobile app (iOS/Android)
- [ ] Advanced analytics and reporting
- [ ] Loyalty program and rewards
- [ ] Multiple payment gateways
- [ ] Email reminders for upcoming shows
- [ ] Seat availability real-time updates
- [ ] Advanced search and filtering
- [ ] User reviews and ratings system

---

**Last Updated**: February 2026
**Current Version**: 1.0.0
"# online_movie-ticket-booking" 
