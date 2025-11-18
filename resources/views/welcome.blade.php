@extends('layouts.app')

@section('seo')
<x-seo 
    title="Mechanic Africa - Professional Car Maintenance & Oil Change Services in Nigeria"
    description="Expert car maintenance, oil change, and vehicle servicing across Nigeria. Certified mechanics, genuine parts, transparent pricing. Book your 4, 6, or 8-cylinder engine service today from ₦60,000."
    keywords="car maintenance Nigeria, oil change Lagos, vehicle servicing Abuja, auto mechanic Nigeria, car repair services, engine oil change, certified mechanics, genuine auto parts, car service package, mobile mechanic, auto workshop Nigeria, vehicle inspection, car diagnostics"
    :schema="view('components.schema.organization-service')->render()"
/>
@endsection

@section('content')
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo">
                <span class="logo-text">
                    <span class="top">MECHANIC</span>
                    <span class="bottom">AFRICA</span>
                </span>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#faq">Partners</a></li>
                <li><a href="#faq">Technicians</a></li>
                <li><a href="#contact" class="contact-btn">Contact us</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Keep Your Car Running Smoothly — The Smart Way.</h1>
            <p>Your car deserves expert care. With Mechanic Africa, you get more than an oil change — you get peace of mind and performance that lasts.</p>
            <a href="#pricing" class="cta-button">Get Started</a>
        </div>
        <div class="hero-images">
            <img src="{{ asset('images/mechanic-working-on-vehicle.jpg') }}" alt="Professional mechanic working on vehicle - Mechanic Africa expert car maintenance services">
            <img src="{{ asset('images/modern-automotive-workshop.jpg') }}" alt="Modern automotive workshop - State-of-the-art car service facility in Nigeria">
            <img src="{{ asset('images/advanced-diagnostic-equipment.jpg') }}" alt="Advanced diagnostic equipment - Professional vehicle inspection and maintenance tools">
        </div>
    </section>

    <!-- Why Choose Section -->
    <section id="services" class="why-choose">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose Mechanic Africa</h2>
                <p>We are redefining car maintenance across Africa starting with Nigeria  — combining certified expertise, genuine parts, and digital convenience to keep your car performing at its best.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <img src="{{ asset('images/verified-mechanics.jpg') }}" alt="Verified mechanics - Certified and trained automotive professionals at Mechanic Africa Nigeria">
                    <div class="feature-content">
                        <h3>Verified Mechanics</h3>
                        <p>Handled by trained, certified professionals who deliver quality service with precision and care.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="{{ asset('images/genuine-oil-parts.jpg') }}" alt="Genuine oils and parts - OEM-approved automotive parts and engine oils in Nigeria">
                    <div class="feature-content">
                        <h3>Genuine Oils & Parts</h3>
                        <p>We use only OEM-approved oils and verified parts to protect your engine and extend its lifespan.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="{{ asset('images/transparent-pricing.jpg') }}" alt="Transparent pricing - Honest and upfront car maintenance costs with no hidden fees">
                    <div class="feature-content">
                        <h3>Transparent Pricing</h3>
                        <p>No hidden charges or surprise costs — just honest, upfront pricing for every plan and service.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <img src="{{ asset('images/nation-wide-access.jpg') }}" alt="Nationwide access - Car maintenance services available across all major Nigerian cities">
                    <div class="feature-content">
                        <h3>Nationwide Access</h3>
                        <p>With Mechanic Africa, you’re never far from quality service. Choose between convenient home service or professional care at our verified partner centers across Nigeria.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Choose the Smart Plan That Fits Your Engine</h2>
                <p>Your engine deserves expert care. Our plans make car maintenance simple, affordable, and built for Nigerian roads.</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>4 - Cylinder</h3>
                    <div class="price">₦60,000</div>
                    <p class="pricing-note">Other service offering at no cost</p>
                    <ul class="features-list">
                        <li>Brake pads/discs assessment</li>
                        <li>Spark plugs check and brake fluid check</li>
                        <li>Ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                    </ul>
                    <a href="#contact-form?plan=4-cylinders" class="buy-button" style="display: block; text-align: center; text-decoration: none;">Buy Plan Now</a>
                </div>
                <div class="pricing-card">
                    <h3>7 - Cylinder</h3>
                    <div class="price">₦70,000</div>
                    <p class="pricing-note">Other service offering at no cost</p>
                    <ul class="features-list">
                        <li>Brake pads/discs assessment</li>
                        <li>Spark plugs check and brake fluid check</li>
                        <li>Ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                    </ul>
                    <a href="#contact-form?plan=7-cylinders" class="buy-button" style="display: block; text-align: center; text-decoration: none;">Buy Plan Now</a>
                </div>
                <div class="pricing-card">
                    <h3>8 - Cylinder</h3>
                    <div class="price">₦90,000</div>
                    <p class="pricing-note">Other service offering at no cost</p>
                    <ul class="features-list">
                        <li>Brake pads/discs assessment</li>
                        <li>Spark plugs check and brake fluid check</li>
                        <li>Ignition coils check</li>
                        <li>Transmission fluid check</li>
                        <li>Headlights/rear lights check</li>
                        <li>Serpentine belt check</li>
                        <li>Coolant check</li>
                    </ul>
                    <a href="#contact-form?plan=8-cylinders" class="buy-button" style="display: block; text-align: center; text-decoration: none;">Buy Plan Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="testimonials-container">
            <div class="testimonial-image">
                <img src="{{ asset('images/customer-testimonials.jpg') }}" alt="Customer testimonials - Happy Mechanic Africa clients sharing their car maintenance experience in Nigeria">
            </div>
            <div class="testimonial-content">
                <div class="testimonial-header">Reviews</div>
                <h2>CUSTOMER TESTIMONIALS</h2>
                <p class="testimonial-text">Real feedback from real drivers. Our customers share how consistent care and professional service have transformed their driving experience.</p>
                <div class="testimonial-quote">
                    "Professional, timely, and transparent service. My car feels brand new again!" — Chinedu A., Hyundai Sonata Owner
                </div>
                <!-- <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div class="author-info">
                        <h4>Iroribuilar Paul</h4>
                        <p>Product Designer</p>
                    </div>
                </div> -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Ready to give your vehicle the professional care it deserves? Contact us today to book your service or learn more about our offerings.</p>
                <div class="contact-details">
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-text">
                            <h4>Phone Number</h4>
                            <p>+234 915 973 7815</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p>info@mechanicafrica.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-text">
                            <h4>Location</h4>
                            <p>Centers across Africa coming soon</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-text">
                            <h4>Location</h4>
                            <p>Serving all major cities across Nigeria</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact-form" id="contact-form">
                <h3>Request a Service</h3>
                <form id="contactForm">
                    
                    @csrf
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Paul Iroribuilar" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Pauliroribuilar@gmail.com" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label>Select Service Package</label>
                        <select id="package" name="package" required>
                            <option value="">Choose your plan</option>
                            <option value="4-cylinders">4 Cylinders - 60k</option>
                            <option value="7-cylinders">6 Cylinders - 70k</option>
                            <option value="8-cylinders">8 Cylinders - 90k</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Car Information</label>
                        <input type="text" id="car" name="car" placeholder="2024 Toyota Corolla" maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Additional Message (Optional)</label>
                        <textarea id="message" name="message" placeholder="Tell us about the service you need" maxlength="1000"></textarea>
                    </div>
                    <!-- reCAPTCHA -->
                    <div class="form-group captcha-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'; ?>"></div>
                    </div>
                    <div id="formMessage"></div>
                    <button type="submit" class="submit-button submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Partnership Section -->
    <section id="faq" class="faq">
        <div class="container">
            <!-- Join Network Section -->
            <div class="partnership-container">
                <div class="partnership-content">
                    <h2>Join The Mechanic Africa Network.</h2>
                    <p>Own a workshop? Partner with us to grow your business and join a trusted network of verified auto professionals. Reach more customers, gain credibility, and access steady, high-quality maintenance jobs across Nigeria.</p>
                    <a href="javascript:void(0)" onclick="openPartnerModal()" class="join-btn">Join Now</a>
                </div>
                <div class="partnership-image">
                    <img src="{{ asset('images/join-mechanic-africa-network.jpg') }}" alt="Join Mechanic Africa workshop partnership network - Auto professionals collaboration in Nigeria">
                </div>
            </div>

            <!-- Technician Section -->
            <div class="technician-container">
                <div class="technician-image">
                    <img src="{{ asset('images/empowring-technicians.jpg') }}" alt="Empowering automotive technicians - Career growth and job opportunities in Nigeria">
                </div>
                <div class="technician-content">
                    <h2>Empowering Technicians, One Repair At A Time.</h2>
                    <p>Are you a skilled technician? Partner with us to access verified jobs, build credibility, and reach more customers. Grow your career with steady work and trusted support across Nigeria.</p>
                    <a href="javascript:void(0)" onclick="openTechnicianModal()" class="join-btn">Join Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
        <div class="cta-banner-wrapper">
            <div class="cta-banner-content">
                <h2>Your Car Deserves Expert Care.<br>Protect It Today</h2>
                <p>Join thousands of Nigerian drivers who trust Mechanic Africa for reliable maintenance and professional service delivery.</p>
                <a href="#" class="waitlist-button">Join our Waitlist</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-about">
                <h3>MECHANIC AFRICA</h3>
                <p>Professional vehicle maintenance and lubrication services designed for Nigerian roads. Certified mechanics, genuine parts, transparent pricing.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#faq">Partners</a></li>
                    <li><a href="#faq">Technicians</a></li>
                    <li><a href="#contact">Contact us</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Contact Info</h4>
                <p><a href="mailto:">info@mechanicafrica.com</a></p>
                <p>+234 800 MECHANIC (632-4264)</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Mechanic Africa. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                // Handle links with query parameters like #contact-form?plan=4-cylinders
                if (href.includes('?')) {
                    window.location.hash = href;
                    const targetId = href.split('?')[0].substring(1);
                    const target = document.getElementById(targetId);
                    if (target) {
                        setTimeout(() => {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 50);
                    }
                } else {
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle plan selection from hash
            function handlePlanFromHash() {
                const hash = window.location.hash;
                const packageSelect = document.getElementById('package');
                
                if (hash && packageSelect) {
                    // Parse hash like #contact-form?plan=4-cylinders
                    const hashParts = hash.split('?');
                    if (hashParts.length > 1) {
                        const params = new URLSearchParams(hashParts[1]);
                        const plan = params.get('plan');
                        
                        // XSS PROTECTION: Validate plan against whitelist
                        const allowedPlans = ['4-cylinders', '7-cylinders', '8-cylinders'];
                        
                        if (plan && allowedPlans.includes(plan)) {
                            // Safe to set - plan is validated
                            packageSelect.value = plan;
                            const formSection = document.getElementById('contact-form');
                            if (formSection) {
                                setTimeout(() => {
                                    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }, 100);
                            }
                        }
                    }
                }
            }
            
            // Handle on page load
            handlePlanFromHash();
            
            // Handle when hash changes (for navigation)
            window.addEventListener('hashchange', handlePlanFromHash);
            
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Clear previous messages
                    const formMessage = document.getElementById('formMessage');
                    formMessage.className = '';
                    formMessage.style.display = 'none';
                    
                    const submitBtn = this.querySelector('.submit-btn');
                    const originalText = submitBtn.textContent;
                    
                    // Get form data
                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const packageSelected = document.getElementById('package').value;
                    const car = document.getElementById('car').value.trim();
                    const captchaResponse = grecaptcha.getResponse();
                    
                    // Validation
                    if (!name || !email || !packageSelected || !car) {
                        formMessage.textContent = 'Please fill in all required fields.';
                        formMessage.className = 'error';
                        formMessage.style.display = 'block';
                        return;
                    }
                    
                    if (!captchaResponse) {
                        formMessage.textContent = 'Please complete the CAPTCHA verification.';
                        formMessage.className = 'error';
                        formMessage.style.display = 'block';
                        return;
                    }
                    
                    // Show loading
                    submitBtn.textContent = 'Sending...';
                    submitBtn.disabled = true;
                    
                    // Prepare form data
                    const formData = new FormData();
                    formData.append('name', name);
                    formData.append('email', email);
                    formData.append('package', packageSelected);
                    formData.append('car', car);
                    formData.append('g-recaptcha-response', captchaResponse);
                    
                    // Submit
                    fetch('{{ route('contact.submit') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            formMessage.textContent = data.message || 'Thank you! Your request has been submitted successfully.';
                            formMessage.className = 'success';
                            formMessage.style.display = 'block';
                            contactForm.reset();
                            grecaptcha.reset();
                        } else {
                            formMessage.textContent = data.message || 'An error occurred. Please try again.';
                            formMessage.className = 'error';
                            formMessage.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        formMessage.textContent = 'An error occurred. Please try again.';
                        formMessage.className = 'error';
                        formMessage.style.display = 'block';
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });
    </script>

    <!-- Partner Registration Modal -->
    <div id="partnerModal" class="partner-modal" style="display: none;">
        <div class="partner-modal-overlay" onclick="closePartnerModal()"></div>
        <div class="partner-modal-content">
            <button class="partner-modal-close" onclick="closePartnerModal()">&times;</button>
            <h2 class="partner-modal-title">Apply for the Position of a Partners</h2>
            
            <form id="partnerForm" class="partner-form">
                @csrf
                
                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name" placeholder="Enter information" maxlength="200" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="registration_number">Company Registration Number</label>
                        <input type="text" id="registration_number" name="registration_number" placeholder="Enter information" maxlength="100" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="Enter information" maxlength="50" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="partner_email">Email Address</label>
                        <input type="email" id="partner_email" name="email" placeholder="Enter Information" maxlength="100" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="technicians_count">Number of Technicians on Site</label>
                        <input type="number" id="technicians_count" name="technicians_count" placeholder="Enter information" min="0" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="years_in_operation">Years in Operation</label>
                        <input type="number" id="years_in_operation" name="years_in_operation" placeholder="Enter information" min="0" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="workshop_address">Workshop Address</label>
                        <input type="text" id="workshop_address" name="workshop_address" placeholder="Enter information" maxlength="500" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="state_city">State/City</label>
                        <input type="text" id="state_city" name="state_city" placeholder="Enter Information" maxlength="100" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="services_offered">Type of Services Offered</label>
                        <input type="text" id="services_offered" name="services_offered" placeholder="Enter information" maxlength="500" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="mobile_mechanic_service">Do you offer Mobile Mechanic Service?</label>
                        <input type="text" id="mobile_mechanic_service" name="mobile_mechanic_service" placeholder="Enter Information" maxlength="10" required>
                    </div>
                </div>

                <div id="partnerFormMessage"></div>
                
                <button type="submit" class="partner-submit-btn">Submit Application</button>
            </form>
        </div>
    </div>

    <!-- Technician Registration Modal -->
    <div id="technicianModal" class="partner-modal" style="display: none;">
        <div class="partner-modal-overlay" onclick="closeTechnicianModal()"></div>
        <div class="partner-modal-content technician-modal-content">
            <button class="partner-modal-close" onclick="closeTechnicianModal()">&times;</button>
            <h2 class="partner-modal-title">Apply for the Position of a Technicians</h2>
            
            <form id="technicianForm" class="partner-form">
                @csrf
                
                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="tech_full_name">Full Name</label>
                        <input type="text" id="tech_full_name" name="full_name" placeholder="Enter Information" maxlength="200" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="tech_phone_number">Phone Number</label>
                        <input type="tel" id="tech_phone_number" name="phone_number" placeholder="Enter Information" maxlength="50" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="tech_email">Email Address</label>
                        <input type="email" id="tech_email" name="email" placeholder="Enter Information" maxlength="100" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="tech_state_city">State / City</label>
                        <input type="text" id="tech_state_city" name="state_city" placeholder="Enter Information" maxlength="100" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="tech_specialization">Area of Specialization</label>
                        <input type="text" id="tech_specialization" name="area_of_specialization" placeholder="Enter Information" maxlength="200" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="tech_years">Years in Operation</label>
                        <input type="number" id="tech_years" name="years_in_operation" placeholder="Enter Information" min="0" required>
                    </div>
                </div>

                <div class="partner-form-row">
                    <div class="partner-form-group">
                        <label for="tech_work_type">Do you work independently or in a workshop?</label>
                        <input type="text" id="tech_work_type" name="work_type" placeholder="Enter Information" maxlength="100" required>
                    </div>
                    <div class="partner-form-group">
                        <label for="tech_certification">Certification / Training Background</label>
                        <input type="text" id="tech_certification" name="certification_training" placeholder="Enter Information" maxlength="500" required>
                    </div>
                </div>

                <div id="technicianFormMessage"></div>
                
                <button type="submit" class="partner-submit-btn">Submit Application</button>
            </form>
        </div>
    </div>

    <style>
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
            font-family: 'Inter', sans-serif;
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

        #partnerFormMessage {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        #partnerFormMessage.success {
            display: block;
            background-color: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #047857;
        }

        #partnerFormMessage.error {
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
            .partner-modal-content {
                padding: 24px;
            }

            .partner-modal-title {
                font-size: 22px;
                margin-bottom: 24px;
            }

            .partner-form-row {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 16px;
            }
        }
    </style>

    <script>
        function openPartnerModal() {
            document.getElementById('partnerModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closePartnerModal() {
            document.getElementById('partnerModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('partnerForm').reset();
            const formMessage = document.getElementById('partnerFormMessage');
            formMessage.className = '';
            formMessage.style.display = 'none';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePartnerModal();
            }
        });

        // Handle partner form submission
        document.getElementById('partnerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formMessage = document.getElementById('partnerFormMessage');
            formMessage.className = '';
            formMessage.style.display = 'none';
            
            const submitBtn = this.querySelector('.partner-submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route('partner.submit') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success page
                    showPartnerSuccessPage();
                    this.reset();
                } else {
                    formMessage.textContent = data.message || 'An error occurred. Please try again.';
                    formMessage.className = 'error';
                    formMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                formMessage.textContent = 'An error occurred. Please try again.';
                formMessage.className = 'error';
                formMessage.style.display = 'block';
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        function showPartnerSuccessPage() {
            const modalContent = document.querySelector('.partner-modal-content');
            modalContent.innerHTML = `
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="margin: 0 auto 32px; width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); display: flex; align-items: center; justify-content: center;">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #EF4444; display: flex; align-items: center; justify-content: center;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 style="color: #EF4444; font-size: 28px; font-weight: 700; margin-bottom: 20px;">
                        Application Submitted Successfully
                    </h2>
                    
                    <p style="color: #4B5563; font-size: 16px; line-height: 1.6; max-width: 500px; margin: 0 auto 32px;">
                        "Thank you for applying to become a Mechanic Africa Partner. Our verification team will review your application and contact you within 5 working days."
                    </p>
                    
                    <button onclick="closePartnerModalAndReset()" style="background: #EF4444; color: white; padding: 14px 60px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Go Back
                    </button>
                </div>
            `;
        }

        function closePartnerModalAndReset() {
            document.getElementById('partnerModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset modal content to show form again
            setTimeout(() => {
                location.reload();
            }, 300);
        }

        // Technician Modal Functions
        function openTechnicianModal() {
            document.getElementById('technicianModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeTechnicianModal() {
            document.getElementById('technicianModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('technicianForm').reset();
            const formMessage = document.getElementById('technicianFormMessage');
            formMessage.className = '';
            formMessage.style.display = 'none';
        }

        // Handle technician form submission
        document.getElementById('technicianForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formMessage = document.getElementById('technicianFormMessage');
            formMessage.className = '';
            formMessage.style.display = 'none';
            
            const submitBtn = this.querySelector('.partner-submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route('technician.submit') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success page
                    showTechnicianSuccessPage();
                    this.reset();
                } else {
                    formMessage.textContent = data.message || 'An error occurred. Please try again.';
                    formMessage.className = 'error';
                    formMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                formMessage.textContent = 'An error occurred. Please try again.';
                formMessage.className = 'error';
                formMessage.style.display = 'block';
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        function showTechnicianSuccessPage() {
            const modalContent = document.querySelector('.technician-modal-content');
            modalContent.innerHTML = `
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="margin: 0 auto 32px; width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); display: flex; align-items: center; justify-content: center;">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #EF4444; display: flex; align-items: center; justify-content: center;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 style="color: #EF4444; font-size: 28px; font-weight: 700; margin-bottom: 20px;">
                        Application Submitted Successfully
                    </h2>
                    
                    <p style="color: #4B5563; font-size: 16px; line-height: 1.6; max-width: 500px; margin: 0 auto 32px;">
                        "Thank you for applying to become a Mechanic Africa Technician. Our verification team will review your application and contact you within 5 working days."
                    </p>
                    
                    <button onclick="closeTechnicianModalAndReset()" style="background: #EF4444; color: white; padding: 14px 60px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Go Back
                    </button>
                </div>
            `;
        }

        function closeTechnicianModalAndReset() {
            document.getElementById('technicianModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Reset modal content to show form again
            setTimeout(() => {
                location.reload();
            }, 300);
        }
    </script>
</body>
</html>

@endsection
