# BEAR-SYSTEM

A comprehensive emergency response management system with React frontend and Node.js backend.

## Project Structure

```
BEAR-SYSTEM/
├── bear-backend/          # Node.js/Express backend
│   ├── config/           # Database configuration
│   ├── controllers/      # Route controllers
│   ├── models/          # Database models
│   ├── routes/          # API routes
│   └── index.js         # Main server file
├── bear-frontend/        # React frontend
│   ├── public/          # Static files
│   ├── src/             # React source code
│   │   ├── components/  # Reusable components
│   │   └── pages/       # Page components
│   └── package.json
└── README.md
```

## Features

- **User Management**: Account creation, verification, and role-based access
- **Incident Management**: Create, track, and manage emergency incidents
- **Dashboard**: Real-time overview of system status
- **Activity Logs**: Comprehensive logging of all system activities
- **Resident Management**: Manage resident information and contacts
- **Responder Management**: Coordinate emergency responders

## Getting Started

### Prerequisites

- Node.js (v14 or higher)
- npm or yarn
- MongoDB (for backend)

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd BEAR-SYSTEM
   ```

2. **Install backend dependencies**
   ```bash
   cd bear-backend
   npm install
   ```

3. **Install frontend dependencies**
   ```bash
   cd ../bear-frontend
   npm install
   ```

4. **Set up environment variables**
   Create `.env` files in both `bear-backend` and `bear-frontend` directories with appropriate configuration.

5. **Start the development servers**

   **Backend:**
   ```bash
   cd bear-backend
   npm start
   ```

   **Frontend:**
   ```bash
   cd bear-frontend
   npm start
   ```

## Available Scripts

### Backend
- `npm start` - Start the development server
- `npm run dev` - Start with nodemon for development
- `npm test` - Run tests

### Frontend
- `npm start` - Start the development server
- `npm build` - Build for production
- `npm test` - Run tests
- `npm eject` - Eject from Create React App

## API Endpoints

- `POST /api/auth/login` - User authentication
- `POST /api/auth/register` - User registration
- `GET /api/incidents` - Get all incidents
- `POST /api/incidents` - Create new incident
- `GET /api/users` - Get all users
- `POST /api/users` - Create new user

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, email support@bearsystem.com or create an issue in this repository.
