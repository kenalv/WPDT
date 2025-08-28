# Changelog

All notable changes to the WordPress Docker Template (WPDT) will be documented in this file.

## [1.0.0] - 2025-08-27

### Added
- Initial release of WordPress Docker Template
- Docker Compose configuration for WordPress + MySQL
- Azure MySQL support with SSL encryption
- Local development environment with phpMyAdmin
- Performance monitoring and optimization
- Autoload options optimization
- Slow query detection and logging
- File upload optimization (128MB limit)
- Security hardening features
- Cross-platform setup scripts (Windows/Linux)
- Comprehensive documentation
- Git repository structure with proper .gitignore

### Features
- **Multi-environment support**: Azure MySQL (production) and Local MySQL (development)
- **Performance monitoring**: Automatic slow query detection and performance logging
- **Security**: SSL encryption, file editing restrictions, security headers
- **Developer tools**: phpMyAdmin, debug logging, performance analysis
- **Easy setup**: One-command setup scripts for different platforms
- **Flexibility**: Environment-based configuration with .env support

### Technical Details
- WordPress: Latest official Docker image
- MySQL: 8.0 with optimized configuration
- PHP: Optimized settings for file uploads and performance
- SSL: Official DigiCert Global Root CA certificate for Azure
- Networks: Isolated Docker networks for security
- Volumes: Persistent data storage with proper permissions

### Documentation
- README.md: Comprehensive project documentation
- QUICK-START.md: Quick reference for common tasks
- Setup scripts: Automated environment setup
- Environment templates: Easy configuration management
