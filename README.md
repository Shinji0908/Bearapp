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
- Git

### For Team Members: How to Access and Set Up the Project

#### Step 1: Clone the Repository
```bash
# Clone the repository to your local machine
git clone https://github.com/Shinji0908/BEAR-SYSTEM.git
cd BEAR-SYSTEM
```

#### Step 2: Install Dependencies

**Backend Setup:**
```bash
cd bear-backend
npm install
```

**Frontend Setup:**
```bash
cd ../bear-frontend
npm install
```

#### Step 3: Environment Configuration

**Backend Environment (.env file in bear-backend folder):**
```env
PORT=5000
MONGODB_URI=mongodb://localhost:27017/bear-system
JWT_SECRET=your-secret-key-here
NODE_ENV=development
```

**Frontend Environment (.env file in bear-frontend folder):**
```env
REACT_APP_API_URL=http://localhost:5000/api
REACT_APP_ENV=development
```

#### Step 4: Database Setup
1. Install MongoDB on your machine
2. Start MongoDB service
3. Create a database named `bear-system`

#### Step 5: Start Development Servers

**Terminal 1 - Backend:**
```bash
cd bear-backend
npm start
# Server will run on http://localhost:5000
```

**Terminal 2 - Frontend:**
```bash
cd bear-frontend
npm start
# React app will run on http://localhost:3000
```

### Team Collaboration Workflow

#### Making Changes
1. **Create a new branch for your feature:**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes and commit:**
   ```bash
   git add .
   git commit -m "Add: description of your changes"
   ```

3. **Push your branch to GitHub:**
   ```bash
   git push origin feature/your-feature-name
   ```

4. **Create a Pull Request** on GitHub to merge your changes

#### Updating Your Local Repository
```bash
# Switch to main branch
git checkout main

# Pull latest changes
git pull origin main

# Delete old feature branch (optional)
git branch -d feature/your-feature-name
```

### Troubleshooting

#### Common Issues:
1. **Port already in use:**
   - Backend: Change PORT in .env file
   - Frontend: React will automatically suggest an alternative port

2. **MongoDB connection failed:**
   - Ensure MongoDB is running
   - Check MONGODB_URI in .env file

3. **Dependencies not found:**
   - Delete node_modules folder and package-lock.json
   - Run `npm install` again

4. **Git authentication issues:**
   - Set up SSH keys or use GitHub CLI
   - Or use personal access token for HTTPS

### Development Guidelines

1. **Code Style:**
   - Use consistent indentation
   - Follow existing naming conventions
   - Add comments for complex logic

2. **Commit Messages:**
   - Use clear, descriptive messages
   - Start with action words: Add, Fix, Update, Remove

3. **Testing:**
   - Test your changes before committing
   - Ensure both frontend and backend work together

### Access Points

- **Frontend Application:** http://localhost:3000
- **Backend API:** http://localhost:5000
- **API Documentation:** http://localhost:5000/api-docs (if available)
- **GitHub Repository:** https://github.com/yourusername/BEAR-SYSTEM

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

