# 🚀 BEAR-SYSTEM Team Setup Guide

## Quick Start for New Team Members

### Prerequisites Checklist
- [ ] Node.js installed (v14 or higher)
- [ ] npm or yarn installed
- [ ] Git installed
- [ ] MongoDB installed and running
- [ ] GitHub account with access to the repository

### Step-by-Step Setup

#### 1. Get the Code
```bash
# Clone the repository
git clone https://github.com/yourusername/BEAR-SYSTEM.git
cd BEAR-SYSTEM
```

#### 2. Backend Setup
```bash
cd bear-backend
npm install

# Create environment file
echo "PORT=5000
MONGODB_URI=mongodb://localhost:27017/bear-system
JWT_SECRET=your-secret-key-here
NODE_ENV=development" > .env
```

#### 3. Frontend Setup
```bash
cd ../bear-frontend
npm install

# Create environment file
echo "REACT_APP_API_URL=http://localhost:5000/api
REACT_APP_ENV=development" > .env
```

#### 4. Start the Application
```bash
# Terminal 1 - Start Backend
cd bear-backend
npm start

# Terminal 2 - Start Frontend
cd bear-frontend
npm start
```

### Access Your Application
- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:5000

## Team Workflow

### Daily Work Process
1. **Start your day:**
   ```bash
   git checkout main
   git pull origin main
   ```

2. **Create feature branch:**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Work on your feature and commit:**
   ```bash
   git add .
   git commit -m "Add: your feature description"
   ```

4. **Push and create Pull Request:**
   ```bash
   git push origin feature/your-feature-name
   # Then go to GitHub and create a Pull Request
   ```

### Common Commands
```bash
# Check status
git status

# See all branches
git branch -a

# Switch branches
git checkout branch-name

# See commit history
git log --oneline

# Discard local changes
git checkout -- .

# Update dependencies
npm install
```

## Troubleshooting

### If Backend Won't Start
- Check if MongoDB is running
- Verify .env file exists in bear-backend
- Check if port 5000 is available

### If Frontend Won't Start
- Check if backend is running on port 5000
- Verify .env file exists in bear-frontend
- Try clearing npm cache: `npm cache clean --force`

### If Git Issues
- Check your Git configuration: `git config --list`
- Verify GitHub access: `git remote -v`

## Need Help?
1. Check the main README.md file
2. Ask in your team chat/communication channel
3. Create an issue on GitHub
4. Check the troubleshooting section above

## Quick Reference
- **Repository:** https://github.com/yourusername/BEAR-SYSTEM
- **Frontend:** http://localhost:3000
- **Backend:** http://localhost:5000
- **Database:** MongoDB on localhost:27017

---
*Last updated: [Current Date]*
*For questions, contact: [Your Contact Info]*
