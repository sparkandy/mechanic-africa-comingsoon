<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Mechanic Africa - Professional Car Maintenance & Oil Change Services in Nigeria')</title>
    <meta name="title" content="@yield('meta_title', 'Mechanic Africa - Professional Car Maintenance & Oil Change Services in Nigeria')">
    <meta name="description" content="@yield('meta_description', 'Expert car maintenance, oil change, and vehicle servicing across Nigeria. Certified mechanics, genuine parts, transparent pricing. Book your 4, 7, or 8-cylinder engine service today from ₦60,000.')">
    <meta name="keywords" content="car maintenance Nigeria, oil change Lagos, vehicle servicing Abuja, auto mechanic Nigeria, car repair services, engine oil change, certified mechanics, genuine auto parts, car service package">
    <meta name="author" content="Mechanic Africa">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Mechanic Africa - Professional Car Maintenance & Oil Change Services in Nigeria')">
    <meta property="og:description" content="@yield('og_description', 'Expert car maintenance, oil change, and vehicle servicing across Nigeria.')">
    <meta property="og:image" content="{{ asset('images/mechanic-working-on-vehicle.jpg') }}">
    <meta property="og:locale" content="en_NG">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Mechanic Africa')">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#EF4444">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* CSS Version: 2025-11-17-FINAL-{{ time() }} */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background-color: #f5f5f5;
        }

        /* Navigation */
        nav {
            background-color: #E8E9ED;
            padding: 20px 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1a1a1a;
            border-radius: 16px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .logo-text {
            color: white;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .logo-text span {
            display: block;
        }

        .logo-text .top {
            letter-spacing: 1px;
        }

        .logo-text .bottom {
            font-size: 14px;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #EF4444;
        }

        .contact-btn {
            background-color: white;
            color: #000000 !important;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(255,255,255,0.1);
        }

        .contact-btn:hover {
            background-color: #f5f5f5;
            transform: translateY(-1px);
            color: #000000 !important;
            box-shadow: 0 4px 8px rgba(255,255,255,0.2);
        }

        /* Hero Section */
        .hero {
            background-color: #E8E9ED;
            padding: 80px 24px 60px;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto 60px;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            color: #000000;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }

        .hero p {
            font-size: 18px;
            color: #4B5563;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            background-color: #1F2937;
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
            text-decoration: none;
        }

        .cta-button:hover {
            background-color: #111827;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .hero-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px;
            background-color: white;
            border-radius: 20px;
            border: 1px solid #E5E7EB;
        }

        .hero-images img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
        }

        /* Why Choose Section */
        .why-choose {
            padding: 100px 24px;
            background-color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .section-header p {
            font-size: 17px;
            color: #6B7280;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .feature-card {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
            border-color: #D1D5DB;
        }

        .feature-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .feature-content {
            padding: 28px;
        }

        .feature-content h3 {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 12px;
        }

        .feature-content p {
            color: #6B7280;
            font-size: 15px;
            line-height: 1.7;
        }

        /* Pricing Section */
        .pricing {
            padding: 100px 24px;
            background-color: #F9FAFB;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .pricing-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px 32px;
            border: 1px solid #E5E7EB;
            transition: all 0.3s;
        }

        .pricing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
            border-color: #D1D5DB;
        }

        .pricing-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 8px;
            text-align: center;
        }

        .price {
            font-size: 40px;
            color: #000000;
            font-weight: 800;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .pricing-note {
            text-align: center;
            color: #6B7280;
            margin-bottom: 28px;
            font-size: 14px;
        }

        .features-list {
            list-style: none;
            margin-bottom: 32px;
        }

        .features-list li {
            padding: 10px 0;
            color: #374151;
            font-size: 14px;
            position: relative;
            padding-left: 24px;
        }

        .features-list li:before {
            content: "●";
            position: absolute;
            left: 0;
            color: #000000;
            font-size: 12px;
        }

        .buy-button {
            width: 100%;
            background-color: #EF4444;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .buy-button:hover {
            background-color: #DC2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Testimonials Section */
        .testimonials {
            padding: 100px 24px;
            background-color: #ffffff;
        }

        .testimonials-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
        }

        .testimonial-image {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        .testimonial-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .testimonial-content {
            padding-right: 40px;
        }

        .testimonial-header {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .testimonial-content h2 {
            font-size: 32px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 24px;
            letter-spacing: -0.5px;
        }

        .testimonial-text {
            color: #6B7280;
            line-height: 1.8;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .testimonial-quote {
            background-color: #F9FAFB;
            padding: 24px;
            border-left: 3px solid #EF4444;
            margin-bottom: 24px;
            font-style: italic;
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
        }

        /* Contact Section */
        .contact-section {
            padding: 100px 24px;
            background-color: #F9FAFB;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-info h2 {
            font-size: 32px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .contact-info > p {
            color: #6B7280;
            margin-bottom: 40px;
            line-height: 1.8;
            font-size: 15px;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            background-color: #EF4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .contact-text h4 {
            color: #000000;
            margin-bottom: 4px;
            font-size: 15px;
            font-weight: 600;
        }

        .contact-text p {
            color: #6B7280;
            margin: 0;
            font-size: 14px;
        }

        .contact-form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
        }

        .contact-form h3 {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background-color: #ffffff;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23374151' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-button {
            width: 100%;
            background-color: #EF4444;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .submit-button:hover {
            background-color: #DC2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .submit-button:disabled {
            background-color: #9CA3AF;
            cursor: not-allowed;
            transform: none;
        }

        .captcha-group {
            margin: 20px 0;
        }

        #formMessage {
            margin: 15px 0;
            padding: 12px;
            border-radius: 6px;
            display: none;
        }

        #formMessage.success {
            display: block;
            background-color: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #047857;
        }

        #formMessage.error {
            display: block;
            background-color: #FEE2E2;
            border: 1px solid #FECACA;
            color: #DC2626;
        }

        /* Partnership Section */
        .faq {
            padding: 100px 24px;
            background-color: #F3F4F6;
        }

        .partnership-container,
        .technician-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto 80px;
            align-items: center;
        }

        .technician-container {
            margin-bottom: 0;
        }

        .partnership-content h2,
        .technician-content h2 {
            font-size: 36px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 20px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .partnership-content p,
        .technician-content p {
            color: #4B5563;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .join-btn {
            background-color: #EF4444;
            color: white;
            padding: 14px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s;
        }

        .join-btn:hover {
            background-color: #DC2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .partnership-image img,
        .technician-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        /* CTA Banner */
        .cta-banner {
            padding: 80px 24px;
            background-color: #E8E9ED;
        }

        .cta-banner-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%231a2332;stop-opacity:1" /><stop offset="50%" style="stop-color:%232a1a1a;stop-opacity:1" /><stop offset="100%" style="stop-color:%231a1a2a;stop-opacity:1" /></linearGradient></defs><rect fill="url(%23grad)" width="1200" height="400"/></svg>');
            background-size: cover;
            background-position: center;
            border-radius: 24px;
            padding: 80px 24px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-banner-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 70% 50%, rgba(239, 68, 68, 0.15) 0%, transparent 60%);
        }

        .cta-banner-content {
            position: relative;
            z-index: 1;
        }

        .cta-banner h2 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .cta-banner p {
            font-size: 18px;
            margin-bottom: 32px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
            color: rgba(255,255,255,0.95);
        }

        .waitlist-button {
            background-color: white;
            color: #000000;
            padding: 14px 40px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
            text-decoration: none;
        }

        .waitlist-button:hover {
            background-color: #f5f5f5;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        /* Footer */
        footer {
            background-color: #000000;
            color: white;
            padding: 60px 24px 40px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 40px;
        }

        .footer-about h3 {
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 700;
        }

        .footer-about p {
            color: #9CA3AF;
            line-height: 1.8;
            font-size: 14px;
        }

        .footer-links h4 {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links ul li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #9CA3AF;
            text-decoration: none;
            transition: color 0.2s;
            font-size: 14px;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-contact h4 {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .footer-contact p {
            color: #9CA3AF;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .footer-contact a {
            color: #9CA3AF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-contact a:hover {
            color: white;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 32px;
            border-top: 1px solid #1F2937;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero h1 { font-size: 44px; }
            .hero-images { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .pricing-grid { grid-template-columns: 1fr; }
            .testimonials-container { grid-template-columns: 1fr; }
            .contact-container { grid-template-columns: 1fr; }
            .partnership-container, .technician-container { grid-template-columns: 1fr; gap: 40px; }
            .footer-container { grid-template-columns: 1fr; gap: 40px; }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero h1 { font-size: 36px; }
            .hero p { font-size: 16px; }
            .section-header h2 { font-size: 32px; }
            .cta-banner h2 { font-size: 36px; }
            .partnership-content h2, .technician-content h2 { font-size: 28px; }
            .testimonial-content { padding-right: 0; }
        }

        /* Partner/Technician Modal Styles */
        .partner-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .partner-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .partner-modal-content {
            position: relative;
            background: white;
            border-radius: 16px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .partner-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border: none;
            background: #F3F4F6;
            color: #6B7280;
            font-size: 24px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .partner-modal-close:hover {
            background: #EF4444;
            color: white;
        }

        .partner-modal-title {
            color: #EF4444;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 32px;
            padding-right: 60px;
        }

        .partner-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .partner-form-group {
            display: flex;
            flex-direction: column;
        }

        .partner-form-group label {
            color: #1F2937;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .partner-form-group input {
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            font-family: "Inter", sans-serif;
            transition: all 0.2s;
        }

        .partner-form-group input:focus {
            outline: none;
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .partner-form-group input::placeholder {
            color: #9CA3AF;
        }

        #partnerFormMessage, #technicianFormMessage {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        #partnerFormMessage.success, #technicianFormMessage.success {
            display: block;
            background-color: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #047857;
        }

        #partnerFormMessage.error, #technicianFormMessage.error {
            display: block;
            background-color: #FEE2E2;
            border: 1px solid #FECACA;
            color: #DC2626;
        }

        .partner-submit-btn {
            width: 100%;
            padding: 16px;
            background: #EF4444;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .partner-submit-btn:hover {
            background: #DC2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .partner-submit-btn:active {
            transform: translateY(0);
        }

        .partner-submit-btn:disabled {
            background: #9CA3AF;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .partner-modal-content { padding: 24px; }
            .partner-modal-title { font-size: 22px; margin-bottom: 24px; }
            .partner-form-row { grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
        }
    </style>
    
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    @stack('head')
</head>
<body>
    @yield('content')
    
    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
