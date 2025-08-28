#!/bin/bash
# WPDT Setup Script

echo "=== WordPress Docker Template (WPDT) Setup ==="
echo ""

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "⚠️  Creating .env file from template..."
    cp .env.example .env
    echo "✅ .env file created. Please edit it with your Azure MySQL password."
    echo ""
fi

# Check if Azure SSL certificate exists
if [ ! -f "DigiCertGlobalRootCA.crt.pem" ]; then
    echo "⚠️  Downloading Azure MySQL SSL certificate...(Use your own if available or needed)"
    curl -o DigiCertGlobalRootCA.crt.pem https://cacerts.digicert.com/DigiCertGlobalRootCA.crt.pem
    echo "✅ SSL certificate downloaded."
    echo ""
fi

echo "🚀 Setup complete! Choose your startup option:"
echo ""
echo "For Azure MySQL (production):"
echo "  docker-compose up -d"
echo ""
echo "For Local MySQL (development):"
echo "  docker-compose --profile local up -d"
echo ""
echo "📊 Access points:"
echo "  WordPress: http://localhost:8090"
echo "  phpMyAdmin: http://localhost:8091 (local mode only)"
echo ""
echo "📝 Don't forget to:"
echo "  1. Edit .env with your Azure MySQL credentials"
echo "  2. Create your database in Azure MySQL"
echo ""
