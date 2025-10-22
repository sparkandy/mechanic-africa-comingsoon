#!/bin/bash

# Mechanic Africa Docker Management Script

case "$1" in
    "start")
        echo "🚀 Starting Mechanic Africa website..."
        docker compose up -d
        echo "✅ Website is now running at http://localhost:9000"
        ;;
    "stop")
        echo "🛑 Stopping Mechanic Africa website..."
        docker compose down
        echo "✅ Website stopped"
        ;;
    "restart")
        echo "🔄 Restarting Mechanic Africa website..."
        docker compose down
        docker compose up -d
        echo "✅ Website restarted and running at http://localhost:9000"
        ;;
    "build")
        echo "🔨 Building Mechanic Africa Docker image..."
        docker compose build --no-cache
        echo "✅ Build complete"
        ;;
    "logs")
        echo "📋 Showing logs..."
        docker compose logs -f
        ;;
    "status")
        echo "📊 Container status:"
        docker compose ps
        ;;
    "clean")
        echo "🧹 Cleaning up Docker resources..."
        docker compose down
        docker system prune -f
        echo "✅ Cleanup complete"
        ;;
    *)
        echo "🔧 Mechanic Africa Docker Management"
        echo ""
        echo "Usage: $0 {start|stop|restart|build|logs|status|clean}"
        echo ""
        echo "Commands:"
        echo "  start   - Start the website container"
        echo "  stop    - Stop the website container"
        echo "  restart - Restart the website container"
        echo "  build   - Build the Docker image"
        echo "  logs    - Show container logs"
        echo "  status  - Show container status"
        echo "  clean   - Clean up Docker resources"
        echo ""
        echo "After starting, visit: http://localhost:9000"
        ;;
esac