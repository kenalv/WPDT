# WordPress Docker Template (WPDT)

[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://www.docker.com/)
[![WordPress](https://img.shields.io/badge/WordPress-Latest-blue.svg)](https://wordpress.org/)
[![Azure MySQL](https://img.shields.io/badge/Azure-MySQL-green.svg)](https://azure.microsoft.com/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A production-ready WordPress Docker template with Azure MySQL integration, performance optimizations, and development tools.

## 🚀 Features

- ✅ **Docker Compose** setup with WordPress + MySQL
- ✅ **Azure MySQL** support with SSL encryption
- ✅ **Local development** option with phpMyAdmin
- ✅ **Performance monitoring** and optimization
- ✅ **Security hardening** and best practices
- ✅ **File upload optimization** (128MB limit)
- ✅ **Debug logging** and slow query detection
- ✅ **Easy setup scripts** for Windows and Linux

## 📋 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Azure MySQL database (optional - can use local MySQL)

### 1. Clone and Setup
```bash
git clone <repository-url> my-wordpress-project
cd my-wordpress-project
```

**Windows:**
```powershell
.\setup.bat
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

### 2. Configure Environment
```bash
# Copy and edit environment file
cp .env.example .env
nano .env  # Add your Azure MySQL password
```

### 3. Start WordPress

**Production (Azure MySQL):**
```bash
docker-compose up -d
```

**Development (Local MySQL):**
```bash
docker-compose --profile local up -d
```

## Configuration

### Ports
- WordPress: http://localhost:8090
- phpMyAdmin (local only): http://localhost:8091
- Local MySQL: localhost:3307

### Database Settings
- **Azure MySQL**: Configure in `.env` file with your server details
- **Local MySQL**: `wpdt_local_db` on `localhost:3307`

### Features Included
- ✅ Azure MySQL SSL connection
- ✅ Performance monitoring and debugging
- ✅ File upload optimization (64MB limit)
- ✅ WordPress security hardening
- ✅ Local development option
- ✅ phpMyAdmin for database management

## File Structure
```
WPDT/
├── docker-compose.yml          # Main configuration
├── .env                        # Environment variables
├── DigiCertGlobalRootCA.crt.pem # Azure SSL certificate
├── uploads.ini                 # PHP upload settings
├── performance-monitor.php     # Performance monitoring
└── wp-content/                 # WordPress content directory
```

## Environment Variables
Create a `.env` file with:
```
DB_PASSWORD=your_azure_mysql_password_here
```

## Database Setup
Before first run, create your database in your Azure MySQL instance.

## Performance Features
- Autoload optimization
- Query monitoring
- Slow query detection
- Memory and execution time limits optimized
- Debug logging enabled

## Switching Between Local and Azure
- **Azure (default)**: `docker-compose up -d`
- **Local**: `docker-compose --profile local up -d`

## Troubleshooting
- Check logs: `docker-compose logs wordpress`
- Monitor performance: Check `/wp-content/debug.log`
- Database access: Use phpMyAdmin at localhost:8091 (local mode)
