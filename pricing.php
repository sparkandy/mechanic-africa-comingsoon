<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan - Mechanic Africa</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .pricing-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 60px 20px;
        }
        
        .pricing-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .pricing-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .pricing-header h1 {
            font-size: 3rem;
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .pricing-header p {
            font-size: 1.1rem;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .plan-card {
            background: white;
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid #e0e0e0;
        }
        
        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(231, 76, 60, 0.2);
            border-color: #e74c3c;
        }
        
        .plan-card.featured {
            border-color: #e74c3c;
            position: relative;
        }
        
        .plan-card.featured::before {
            content: "POPULAR";
            position: absolute;
            top: -15px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .plan-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .plan-name {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 15px;
        }
        
        .plan-price {
            font-size: 3.5rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .plan-price span {
            font-size: 1.5rem;
            color: #666;
        }
        
        .plan-subtitle {
            font-size: 1rem;
            color: #888;
            margin-top: 10px;
        }
        
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }
        
        .plan-features li {
            padding: 12px 0;
            color: #666;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        
        .plan-features li::before {
            content: "●";
            color: #333;
            font-weight: bold;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .plan-button {
            width: 100%;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .plan-button:hover {
            background: #c0392b;
            transform: scale(1.02);
        }
        
        .plan-button:active {
            transform: scale(0.98);
        }
        
        .pricing-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin-top: 40px;
            text-align: center;
        }
        
        .pricing-note strong {
            font-weight: 600;
        }
        
        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #e74c3c;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 30px;
            transition: color 0.3s ease;
        }
        
        .back-home:hover {
            color: #c0392b;
        }
        
        @media (max-width: 768px) {
            .pricing-header h1 {
                font-size: 2rem;
            }
            
            .pricing-header p {
                font-size: 1rem;
            }
            
            .plans-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .plan-price {
                font-size: 2.5rem;
            }
            
            .plan-card.featured::before {
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="pricing-page">
        <div class="pricing-container">
            <a href="index.php" class="back-home">← Back to Home</a>
            
            <div class="pricing-header">
                <h1>Choose Your Plan</h1>
                <p>Keep your car in top shape with Mechanic Africa's Oil Change & Maintenance Plan — smooth performance, healthy engine, fewer repairs.</p>
            </div>
            
            <div class="plans-grid">
                <!-- 4 Cylinders Plan -->
                <div class="plan-card">
                    <div class="plan-header">
                        <div class="plan-name">4 Cylinders</div>
                        <div class="plan-price">60<span>k</span></div>
                    </div>
                    
                    <div class="plan-subtitle">Other service offering at no cost</div>
                    
                    <ul class="plan-features">
                        <li>Brake pads/discs assessment</li>
                        <li>spark plugs check</li>
                        <li>ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Brake fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                        <li>Advisory</li>
                    </ul>
                    
                    <a href="index.php?plan=4-cylinders#contact-form" class="plan-button">Get Started</a>
                </div>
                
                <!-- 7 Cylinders Plan -->
                <div class="plan-card featured">
                    <div class="plan-header">
                        <div class="plan-name">7 Cylinders</div>
                        <div class="plan-price">70<span>k</span></div>
                    </div>
                    
                    <div class="plan-subtitle">Other service offering at no cost</div>
                    
                    <ul class="plan-features">
                        <li>Brake pads/discs assessment</li>
                        <li>spark plugs check</li>
                        <li>ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Brake fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                        <li>Advisory</li>
                    </ul>
                    
                    <a href="index.php?plan=7-cylinders#contact-form" class="plan-button">Get Started</a>
                </div>
                
                <!-- 8 Cylinders Plan -->
                <div class="plan-card">
                    <div class="plan-header">
                        <div class="plan-name">8 Cylinders</div>
                        <div class="plan-price">90<span>k</span></div>
                    </div>
                    
                    <div class="plan-subtitle">Other service offering at no cost</div>
                    
                    <ul class="plan-features">
                        <li>Brake pads/discs assessment</li>
                        <li>spark plugs check</li>
                        <li>ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Brake fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                        <li>Advisory</li>
                    </ul>
                    
                    <a href="index.php?plan=8-cylinders#contact-form" class="plan-button">Get Started</a>
                </div>
            </div>
            
            <div class="pricing-note">
                <strong>Note:</strong> This plan exclude all luxury cars ( e.g Benz, range Rover, Bentley etc)
            </div>
        </div>
    </div>
</body>
</html>