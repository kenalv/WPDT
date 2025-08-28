# 🚀 WPDT Quick Start Commands

## Initial Setup
```bash
# 1. Navigate to WPDT folder
cd WPDT

# 2. Run setup script (Windows)
setup.bat

# 3. Edit .env file with your Azure MySQL password
notepad .env
```

## Startup Options

### Option 1: Azure MySQL (Production)
```bash
# Start with Azure database
docker-compose up -d

# Access WordPress
open http://localhost:8090
```

### Option 2: Local MySQL (Development)
```bash
# Start with local database
docker-compose --profile local up -d

# Access WordPress and phpMyAdmin
open http://localhost:8090
open http://localhost:8091
```

### Option 3: Mixed Development (Local DB + Override)
```bash
# Use local database with additional override
docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
```

## Management Commands

```bash
# View logs
docker-compose logs wordpress
docker-compose logs mysql

# Stop services
docker-compose down

# Stop and remove volumes (fresh start)
docker-compose down -v

# Restart WordPress only
docker-compose restart wordpress

# Access WordPress container shell
docker exec -it wpdt-wordpress bash

# Access local MySQL
docker exec -it wpdt-mysql-local mysql -u wpdt -p
```

## Database Operations

### Create Azure Database
```sql
-- Connect to Azure MySQL and create your database
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Check Performance
```bash
# View WordPress debug logs
docker exec wpdt-wordpress tail -f /var/www/html/wp-content/debug.log

# Check database connection
docker exec wpdt-wordpress php -r "
require '/var/www/html/wp-config.php';
echo 'Database: ' . DB_NAME . '\n';
echo 'Host: ' . DB_HOST . '\n';
"
```

## Troubleshooting

### Common Issues
1. **Port conflicts**: Change ports in docker-compose.yml
2. **Database connection**: Check .env file and Azure database name
3. **SSL errors**: Ensure DigiCertGlobalRootCA.crt.pem is downloaded
4. **Performance**: Check debug.log for slow queries

### Reset Everything
```bash
# Complete reset (removes all data)
docker-compose down -v
docker system prune -f
rm -rf wp-content/*
docker-compose up -d
```

## File Structure
```
WPDT/
├── docker-compose.yml              # Main Docker configuration
├── docker-compose.local.yml        # Local development override
├── .env                           # Environment variables (create from .env.example)
├── .env.example                   # Environment template
├── DigiCertGlobalRootCA.crt.pem   # Azure SSL certificate
├── uploads.ini                    # PHP configuration
├── performance-monitor.php        # Performance monitoring plugin
├── setup.bat / setup.sh           # Setup scripts
└── wp-content/                    # WordPress content directory
    ├── mu-plugins/                # Must-use plugins
    ├── themes/                    # Custom themes
    ├── plugins/                   # Plugins
    └── uploads/                   # Media uploads
```
