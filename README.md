## OceanEase 
OceanEase is a web-based Cruise Ship Management System designed to simplify cruise booking, cabin reservations, onboard services, and cruise operations.
It allows users to explore cruises, book cabins, manage profiles, and view bookings, while admins can efficiently manage cruises, ships, cabins, bookings, users, and customer inquiries.
The application is built using PHP, MySQL, HTML, CSS, and JavaScript with a clean and responsive UI.

## 📸 Demo / Live Link
🔗 Live Preview: 
📂 Repository Link:https://github.com/kavyashree-1801/UST-Project_oceanease.git

## Features
👤 Voyager Features
- Username and password authentication
- Secure sign-in
- Ordering catering items
- Ordering stationery items
- Booking resort movie tickets
- Booking beauty salon services
- Booking fitness center sessions
- Booking party hall
- View booking and order status

🛠 Admin Features

- Admin authentication and dashboard
- Add new catering and stationery items
- Edit existing items
- Delete items
- Maintain menu and inventory items
- Register new voyagers
- Manage system data efficiently

  📊 Manager Features

- View booked resort movie tickets
- View booked beauty salon services
- View booked fitness center sessions
- View booked party hall reservations
- Monitor overall service utilization

  🍽 Head Cook Features

- View catering orders placed by voyagers
- Track ordered catering items
- view Menu Items

  📦 Supervisor Features

- View stationery orders
- Monitor stationery usage
- Track stationery requests from voyagers

  ## Tech Stack

| Layer    | Technology            |
| -------- | --------------------- |
| Backend  | PHP (Procedural)      |
| Database | MySQL / MariaDB       |
| Frontend | HTML, CSS, JavaScript |
| Server   | Apache (XAMPP)        |


## Folder Structure
Oceanease/
│
├── api/                                      # Backend API endpoints
│   ├── auth_api.php                          # Authentication & login
│   ├── forgot_password_api.php               # Forgot / reset password
│   ├── fetch_menu.php                        # Fetch catering & stationery menu
│
│   ├── catering_api.php                      # Catering item management
│   ├── catering_orders_api.php               # Catering order processing
│   ├── headcook_menu_api.php                 # Head cook menu access
│
│   ├── stationery_api.php                    # Stationery item management
│   ├── stationery_items_api.php              # Fetch stationery items
│   ├── stationery_ordders_api.php             # Stationery order handling
│
│   ├── resort_booking_api.php                # Resort movie bookings
│   ├── salon_booking_api.php                 # Beauty salon bookings
│   ├── fitness_booking_api.php               # Fitness center bookings
│   ├── party_booking_api.php                 # Party hall bookings
│
│   ├── manager_resort_movies.php              # Manager – resort movie bookings
│   ├── manager_beauty_salon_bookings.php      # Manager – salon bookings
│   ├── manager_partyhall_bookings.php         # Manager – party hall bookings
│
│   ├── contact_api.php                        # Contact form handling
│   └── feedback_api.php                       # User feedback handling
│
├── css/                                      # Stylesheets
│   ├── about.css
│   ├── admin.css
│   ├── admin_home.css
│   ├── auth.css
│   ├── catering.css
│   ├── contact.css
│   ├── dashboard.css
│   ├── edit_menu.css
│   ├── edit_voyager.css
│   ├── feedback.css
│   ├── fitness.css
│   ├── forgot_password.css
│   ├── headcook_dash.css
│   ├── home.css
│   ├── manage_menu.css
│   ├── manage_voyagers.css
│   ├── manager.css
│   ├── manager_dash.css
│   ├── orders.css
│   ├── party.css
│   ├── reset_password.css
│   ├── resort_booking.css
│   ├── salon.css
│   ├── stationery.css
│   ├── stationery_items.css
│   ├── supervisor_dashboard.css
│   ├── view_catering_orders.css
│   ├── view_fitness.css
│   ├── view_party.css
│   ├── view_resort.css
│   ├── view_salon.css
│   ├── view_stationery.css
│   └── view_stationery_items.css
├── js/                                       # JavaScript files
│   ├── auth.js                               # Authentication logic
│   ├── forgot_password.js                   # Forgot password handling
│   ├── reset_password.js                    # Reset password handling
│
│   ├── catering.js                          # Catering item interactions
│   ├── catering_orders.js                   # Catering order processing
│   ├── orders.js                            # Common order handling logic
│
│   ├── stationery.js                        # Stationery ordering
│   ├── stationery_items.js                  # Stationery items display
│
│   ├── fitness.js                           # Fitness center bookings
│   ├── salon.js                             # Beauty salon bookings
│   ├── party.js                             # Party hall bookings
│   ├── resort_booking.js                   # Resort movie bookings
│
│   ├── supervisor.js                        # Supervisor dashboard logic
│
│   ├── view_menu.js                         # View menu items
│   ├── view_beauty_salon.js                 # Manager – salon bookings
│   ├── view_fitness.js                      # Manager – fitness bookings
│   ├── view_partyhall.js                    # Manager – party hall bookings
│   ├── view_resort_movies.js                # Manager – resort movie bookings
│   ├── view_stationery.js                   # Supervisor – stationery orders
│   └── view_stationery_items.js             # View stationery items
│
├── uploads/                                  # Uploaded files (if any)
│
├── config.php                                # Database connection
├── auth.php                                  # Login page
├── logout.php                                # Logout
├── forgot_password.php                       # forgot password page
├── reset_password.php                        # reset_password page
├── about.php                                 # about us page
├── contact.php                               # contact us page                                
├── feedback.php                              # feedback page
│
├── voyager/                                  # Voyager module
│   ├── homepage.php
│   ├── catering.php
│   ├── stationery.php
│   ├── resort_booking.php
│   ├── salon_booking.php
│   ├── fitness_booking.php
│   └── party_booking.php
│
├── admin/                                    # Admin module
│   ├── admin_homepage.php
│   ├── edit_menu.php
│   ├── edit_voyager.php
│   ├── manage_menu.php
│   ├── manage_voyager.php
│
├── manager/                                  # Manager module
│   ├── manager_dashboard.php
│   ├── view_resort_movies.php
│   ├── view_beauty_salon.php
│   ├── view_fitness.php
│   └── view_partyhall.php
│
├── headcook/                                 # Head Cook module
│   ├── headcook_dashboard.php
│   └── view_catering_orders.php
│    └── view_menu.php
│
├── supervisor/                               # Supervisor module
│   ├── supervisor_dashboard.php
│   └── view_stationery_orders.php
│   └── view_stationery_items.php
│
└── README.md 

 ## Installation
- Clone the repository
- Place the project inside the server root directory
(e.g., htdocs in XAMPP)
- Create a MySQL database
- Import the provided SQL file
- Configure database credentials in config.php
- Start Apache and MySQL
- Access the application in the browser:
   http://localhost/oceanease/

 ## Future Enhancements
- Email and SMS booking notifications
- Mobile-responsive / PWA support
- Analytics dashboard for managers
- Online payment integration
- Inventory auto-management
- AI-based booking recommendations  

## Contact
Project owner / maintainer
Kavyashree D M

📩 Email: kavyashreedmmohan@gmail.com

## ⭐ Support
If you like this project, please ⭐ the repo!
