<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceHub - Premium Auto Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #ff5722; --dark: #0f172a; --light: #f8fafc; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--dark); color: white; overflow-x: hidden; }
        
        /* --- GLASS NAVBAR --- */
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px 50px; 
            background: rgba(15, 23, 42, 0.7); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
            position: fixed; 
            width: 100%; 
            top: 0; 
            z-index: 1000; 
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo { 
            font-size: 1.5rem; 
            font-weight: 800; 
            color: white; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        } 
        .logo i { color: var(--primary); }

        /* --- NAVIGATION LINKS --- */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px; 
        }

        .nav-links a { 
            color: #cbd5e1; 
            text-decoration: none; 
            font-weight: 500; 
            transition: 0.3s; 
            font-size: 0.95rem;
        }

        .nav-links a:not(.btn-login):hover { 
            color: var(--primary); 
            text-shadow: 0 0 10px rgba(255, 87, 34, 0.5); 
        }

        /* --- LOGIN BUTTON --- */
        .btn-login { 
            background: var(--primary); 
            padding: 18px 40px; 
            border-radius: 50px; 
            color: white !important; 
            font-weight: 700; 
            transition: 0.3s;
            display: inline-flex; 
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .btn-login:hover { 
            background: rgba(255, 87, 34, 0.8); 
            backdrop-filter: blur(5px); 
            box-shadow: 0 0 20px rgba(255, 87, 34, 0.4); 
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero { 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            background: linear-gradient(rgba(15,23,42,0.8), rgba(15,23,42,0.8)), url('https://images.unsplash.com/photo-1487754180451-c456f719a1fc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); 
            background-size: cover; 
            background-position: center; 
            padding: 0 20px; 
        }
        .hero-content h1 { font-size: 4rem; margin-bottom: 20px; line-height: 1.1; } 
        .hero-content span { color: var(--primary); }
        .hero-content p { font-size: 1.2rem; color: #94a3b8; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto; }
        
        /* CTA Button */
        .cta-btn { 
            background: var(--primary); color: white; padding: 18px 40px; font-size: 1.1rem; text-decoration: none; border-radius: 8px; font-weight: 700; transition: 0.3s; display: ; 
        }
        .cta-btn:hover { 
            background: rgba(255, 87, 34, 0.3); backdrop-filter: blur(10px); border: 1px solid rgba(255, 87, 34, 0.6); 
            box-shadow: 0 0 30px rgba(255, 87, 34, 0.5); transform: translateY(-5px);
        }

        /* Features */
        .features { padding: 80px 50px; background: white; color: var(--dark); display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
        
        .feature-card { 
            padding: 30px; border-radius: 16px; background: rgba(241, 245, 249, 0.6); backdrop-filter: blur(10px); 
            border: 1px solid rgba(0,0,0,0.05); transition: 0.3s; 
        }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: rgba(255,255,255,0.8); }
        .feature-card i { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo">
            <i class="fa-solid fa-wrench"></i> ServiceHub
        </div>
        
        <div class="nav-links">
            <a href="#services">Services</a>
            <a href="#about">About</a>
            <a href="login.php" class="btn-login">Login / Join</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Expert Care for <br> Your <span>Dream Machine</span></h1>
            <p>Experience the next generation of auto maintenance. Fast, reliable, and tracked entirely online.</p>
            
            <a href="login.php" class="btn-login">Book Appointment Now</a>
        </div>
    </header>

    <section class="features" id="services">
        <div class="feature-card">
            <i class="fa-solid fa-clock"></i>
            <h3>Real-time Tracking</h3>
            <p>Know exactly when your car is being worked on and when it's ready.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            <h3>Expert Mechanics</h3>
            <p>Certified professionals who treat your vehicle like their own.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <h3>Transparent Pricing</h3>
            <p>No hidden fees. You see the estimate before we start the work.</p>
        </div>
    </section>

</body>
</html>