<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Tracker - Alex Driving School</title>
    <meta name="description" content="Check the real-time status of your driving license application with Alex Driving School.">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght=400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> 

    <link rel="stylesheet" type="text/css" href="index.css">

    <style>
        /* Reusing Variables from index.css */
        :root {
            --alex-primary: #004d99; /* Deep Blue */
            --alex-secondary: #ffc800; /* Gold Accent */
            --karuneelam-navy: #00264d; /* Darker Blue */
            --alex-light: #f4f7f9; /* Light Gray/Off-White */
            --alex-dark-text: #333333;
        }

        /* --- Section 1: Hero Banner for Search Page --- */
        .search-hero-banner {
            background: linear-gradient(135deg, var(--karuneelam-navy), var(--alex-primary));
            padding: 80px 0 50px;
            color: white;
            text-align: center;
            border-bottom: 5px solid var(--alex-secondary);
        }
        .search-hero-banner h2 {
            font-size: 3em;
            font-weight: 900;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .search-hero-banner p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        /* --- Section 2: Search Box Container --- */
        .search-form-section {
            padding: 50px 0 80px;
            background-color: var(--alex-light);
        }
        .search-container {
            max-width: 750px;
            margin: -70px auto 0; /* Pulls the box up into the hero banner */
            padding: 40px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10; 
        }
        .search-container h3 {
            text-align: center;
            color: var(--alex-primary);
            margin-bottom: 25px;
            font-weight: 800;
            font-size: 1.8em;
            border-bottom: 3px solid var(--alex-secondary);
            display: inline-block;
            padding-bottom: 5px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: fit-content;
        }
        
        /* Input Field Styling (Sleek) */
        .search-form-group {
            margin-bottom: 30px;
        }
        .search-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--karuneelam-navy);
            font-size: 1.1em;
        }
        .search-form-group input[type="text"] {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 18px;
            background-color: #ffffff;
            transition: border-color 0.4s, box-shadow 0.4s;
        }
        .search-form-group input[type="text"]:focus {
            border-color: var(--alex-secondary);
            box-shadow: 0 0 10px rgba(255, 200, 0, 0.5); /* Gold Glow */
            outline: none;
        }

        /* Submit Button Styling (CTA style) */
        .search-button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 8px;
            background-color: var(--alex-secondary); /* GOLD */
            color: var(--karuneelam-navy); 
            font-size: 20px;
            font-weight: 900;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s, transform 0.1s, box-shadow 0.3s;
        }
        .search-button:hover {
            background-color: #ffd740; /* Lighter gold on hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        .search-button:active {
            transform: translateY(2px);
        }

        /* --- Section 3: Results Display (The Viyappoodra Part) --- */
        #results-area {
            margin-top: 40px;
            padding: 30px;
            border-radius: 10px;
            min-height: 150px;
            background-color: var(--alex-light);
            box-shadow: inset 0 0 10px rgba(0, 77, 153, 0.1); /* Subtle inner blue shadow */
            text-align: left;
            border-left: 5px solid var(--alex-primary);
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-card-title {
            color: var(--karuneelam-navy);
            font-size: 2em;
            font-weight: 800;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .result-card-title i {
            margin-right: 15px;
            color: var(--alex-secondary);
        }

        .result-detail {
            margin-bottom: 12px;
            font-size: 1.1em;
            padding-left: 40px; /* Aligns details below the title icon */
        }
        .result-detail strong {
            color: var(--alex-primary);
        }

        /* Status Badge Styling */
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 900;
            color: white;
            margin-top: 20px;
            font-size: 1.2em;
            letter-spacing: 1px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: pulse 1.5s infinite; /* Added a subtle pulse for pending/approved */
        }
        /* Color Mapping */
        .status-pending { 
            background-color: #f39c12; /* Orange for attention */
            animation: pulse 1.5s infinite;
        } 
        .status-approved { 
            background: linear-gradient(45deg, #16a085, #2ecc71); /* Gradient Green for success */
            animation: none; /* Stop pulsing once approved */
        } 
        .status-rejected, .status-not-found { 
            background-color: #e74c3c; /* Flat Red for rejection/error */
            animation: none;
        }
        .status-not-found {
            text-align: center;
            width: 100%;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 200, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 200, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 200, 0, 0); }
        }
    </style>

</head>
<body>

    <header role="banner">
        <div class="container">
            <h1>Alex <span>Driving School</span></h1> 
            <nav role="navigation" aria-label="Main Navigation">
                <ul>
                    <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li> 
                    <li><a href="index.html#features"><i class="fas fa-check-circle"></i> Features</a></li>
                    <li><a href="registration.php"><i class="fas fa-file-alt"></i> Apply Now</a></li> 
                    <li><a href="index.html#contact"><i class="fas fa-phone-alt"></i> Contact</a></li>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <section class="search-hero-banner">
            <div class="container">
                <h2>REAL-TIME STATUS TRACKER</h2>
                <p>Track Your Driving License Application Progress Instantly.</p>
            </div>
        </section>

        <section class="search-form-section">
            <div class="container">
                <div class="search-container">
                    <h3><i class="fas fa-map-marker-alt"></i> Locate Application</h3>
                    <form id="application-search-form" action="#" method="GET">
                        <div class="search-form-group">
                            <label for="search-id"><i class="fas fa-fingerprint"></i> Application ID / National ID</label>
                            <input type="text" id="search-id" name="id" placeholder="Enter Your Unique ID (e.g., AADS-2025-101 or 123456789V)" required>
                        </div>
                        <button type="submit" class="search-button">
                            <i class="fas fa-search-dollar"></i> GET STATUS NOW!
                        </button>
                    </form>

                    <div id="results-area">
                        <p style="text-align: center; color: var(--alex-dark-text);">
                            <i class="fas fa-info-circle"></i> Enter your ID above and click 'Get Status Now!' to view your application details.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer role="contentinfo">
        <div class="container">
            <p>© 2025 Alex Driving School - The Light Standard in Driving Education. All rights reserved.</p>
            <p>Contact Us: +123 456 7890 | Email: info@alexdrivingschool.com</p>
        </div>
    </footer>

    <script>
        document.getElementById('application-search-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            const searchID = document.getElementById('search-id').value.trim();
            const resultsArea = document.getElementById('results-area');

            // --- Mock Data for Demonstration (Viyappoodra Sample Data) ---
            let applicationData;
            if (searchID.toUpperCase() === 'AADS-2025-101' || searchID.toUpperCase() === '123456789V') {
                applicationData = {
                    id: 'AADS-2025-101',
                    name: 'Gowri K.',
                    course: 'Heavy Vehicle License (HVL)',
                    icon: 'fas fa-truck-moving',
                    dateApplied: '2025-11-20',
                    status: 'Pending Review',
                    statusClass: 'status-pending',
                    nextStep: 'Awaiting final document verification by the Administration team. Please wait 24 hours.'
                };
            } else if (searchID.toUpperCase() === 'AADS-2025-202' || searchID.toUpperCase() === '987654321X') {
                applicationData = {
                    id: 'AADS-2025-202',
                    name: 'Velu S.',
                    course: 'Light Motor Vehicle (LMV)',
                    icon: 'fas fa-car',
                    dateApplied: '2025-10-05',
                    status: 'Approved!',
                    statusClass: 'status-approved',
                    nextStep: 'Congratulations! Your theory class is scheduled for 2025-12-15 at 10:00 AM. Check your email for details.'
                };
            } else {
                applicationData = {
                    status: 'Not Found',
                    statusClass: 'status-not-found',
                    nextStep: `<i class="fas fa-exclamation-triangle"></i> Application or ID **'${searchID}'** was not found. Please ensure the ID is correct and try again. Contact support if the issue persists.`,
                    id: searchID
                };
            }
            // --- End Mock Data ---


            // Display Results
            let resultsHTML = '';
            if (applicationData.status === 'Not Found') {
                resultsHTML = `
                    <div class="status-badge ${applicationData.statusClass}">STATUS: ${applicationData.status}</div>
                    <p style="text-align: center; margin-top: 20px;">${applicationData.nextStep}</p>
                `;
            } else {
                resultsHTML = `
                    <div class="result-card-title"><i class="${applicationData.icon}"></i> Application: ${applicationData.id}</div>
                    <div class="result-detail"><strong>Applicant Name:</strong> ${applicationData.name}</div>
                    <div class="result-detail"><strong>Selected Course:</strong> ${applicationData.course}</div>
                    <div class="result-detail"><strong>Application Date:</strong> ${applicationData.dateApplied}</div>
                    <div class="result-detail" style="margin-top: 20px;">
                        <span class="status-badge ${applicationData.statusClass}">Status: ${applicationData.status}</span>
                    </div>
                    <div class="result-detail" style="margin-top: 25px; border-top: 1px solid #ddd; padding-top: 15px;">
                        <strong><i class="fas fa-bullhorn"></i> Important Next Step:</strong> ${applicationData.nextStep}
                    </div>
                `;
            }
            resultsArea.innerHTML = resultsHTML;
        });
    </script>
</body>
</html>