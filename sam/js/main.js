// Gestion du scroll et animations
document.addEventListener('DOMContentLoaded', () => {
    // Animation du header au scroll
    const header = document.querySelector('header');
    const scrollThreshold = 50;

    window.addEventListener('scroll', () => {
        if (window.scrollY > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Animations au scroll pour les sections
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Ajouter la classe animate-on-scroll à tous les éléments à animer
    document.querySelectorAll('section > *').forEach(element => {
        element.classList.add('animate-on-scroll');
        observer.observe(element);
    });

    // Animation des compétences
    document.querySelectorAll('.skill-category').forEach((skill, index) => {
        skill.style.animationDelay = `${index * 0.2}s`;
        observer.observe(skill);
    });

    // Menu Dropdown Toggle
	if($('.menu-trigger').length){
		$(".menu-trigger").on('click', function() {	
			$(this).toggleClass('active');
			$('.header-area .nav').slideToggle(200);
		});
	}


	// Menu elevator animation
	$('.scroll-to-section a[href*=\\#]:not([href=\\#])').on('click', function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				var width = $(window).width();
				if(width < 991) {
					$('.menu-trigger').removeClass('active');
					$('.header-area .nav').slideUp(200);	
				}				
				$('html,body').animate({
					scrollTop: (target.offset().top) - 80
				}, 700);
				return false;
			}
		}
	});
});

// Smooth scrolling pour les liens de navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        if (href === '#') return;
        const target = document.querySelector(href);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Gestion des liens actifs dans la navigation
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links a');
    
    // Obtenir la position actuelle du scroll
    const scrollPosition = window.scrollY + 100;
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

// Mettre à jour le lien actif au scroll
window.addEventListener('scroll', updateActiveNavLink);
window.addEventListener('load', updateActiveNavLink);

// Gestion du formulaire de contact avec validation
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Récupération et validation des données
        const formData = new FormData(this);
        const formValues = Object.fromEntries(formData.entries());
        
        // Validation basique
        let isValid = true;
        let errorMessage = '';
        
        if (!formValues.name || formValues.name.trim() === '') {
            isValid = false;
            errorMessage += 'Le nom est requis.\n';
        }
        
        if (!formValues.email || !isValidEmail(formValues.email)) {
            isValid = false;
            errorMessage += 'Email invalide.\n';
        }
        
        if (!formValues.message || formValues.message.trim() === '') {
            isValid = false;
            errorMessage += 'Le message est requis.\n';
        }
        
        if (!isValid) {
            showNotification(errorMessage, 'error');
            return;
        }
        
        try {
            // Simulation d'envoi (à remplacer par votre logique d'envoi réelle)
            await simulateFormSubmission();
            
            showNotification('Message envoyé avec succès !', 'success');
            this.reset();
            
        } catch (error) {
            showNotification('Erreur lors de l\'envoi du message. Veuillez réessayer.', 'error');
        }
    });
}

// Gestion du formulaire de contact
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const messageInput = document.getElementById('message');
    const charCount = document.querySelector('.char-count');
    const maxLength = 500;

    // Mise à jour du compteur de caractères
    messageInput.addEventListener('input', function() {
        const remaining = maxLength - this.value.length;
        charCount.textContent = `${this.value.length} / ${maxLength} caractères`;
        
        if (this.value.length > maxLength) {
            this.value = this.value.substring(0, maxLength);
            charCount.style.color = '#ef4444';
        } else {
            charCount.style.color = 'var(--gray-400)';
        }
    });

    // Validation et soumission du formulaire
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('.submit-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnIcon = submitBtn.querySelector('i');
        const originalText = btnText.textContent;
        
        // Validation des champs
        const name = formData.get('name');
        const email = formData.get('email');
        const subject = formData.get('subject');
        const message = formData.get('message');
        
        if (!validateContactForm(name, email, subject, message)) {
            return;
        }

        // Animation du bouton pendant l'envoi
        submitBtn.disabled = true;
        btnText.textContent = 'Envoi en cours...';
        btnIcon.className = 'fas fa-spinner fa-spin';
        
        try {
            // Simuler l'envoi (à remplacer par votre API)
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // Animation de succès
            btnText.textContent = 'Envoyé !';
            btnIcon.className = 'fas fa-check';
            submitBtn.classList.add('success');
            
            showNotification('Message envoyé avec succès !', 'success');
            
            // Réinitialiser le formulaire après un délai
            setTimeout(() => {
                this.reset();
                submitBtn.disabled = false;
                btnText.textContent = originalText;
                btnIcon.className = 'fas fa-paper-plane';
                submitBtn.classList.remove('success');
                charCount.textContent = `0 / ${maxLength} caractères`;
            }, 2000);
            
        } catch (error) {
            console.error('Erreur lors de l\'envoi:', error);
            showNotification('Erreur lors de l\'envoi du message', 'error');
            
            // Restaurer le bouton
            submitBtn.disabled = false;
            btnText.textContent = originalText;
            btnIcon.className = 'fas fa-paper-plane';
        }
    });
});

// Validation du formulaire de contact
function validateContactForm(name, email, subject, message) {
    let isValid = true;
    
    // Validation du nom
    if (!REGEX_PATTERNS.name.test(name)) {
        showFieldError(document.getElementById('name'), 
            'Le nom doit contenir au moins 2 caractères et uniquement des lettres');
        isValid = false;
    }
    
    // Validation de l'email
    if (!REGEX_PATTERNS.email.test(email)) {
        showFieldError(document.getElementById('email'), 
            'Veuillez entrer une adresse email valide');
        isValid = false;
    }
    
    // Validation du sujet
    if (subject.length < 3) {
        showFieldError(document.getElementById('subject'), 
            'Le sujet doit contenir au moins 3 caractères');
        isValid = false;
    }
    
    // Validation du message
    if (message.length < 10) {
        showFieldError(document.getElementById('message'), 
            'Le message doit contenir au moins 10 caractères');
        isValid = false;
    }
    
    return isValid;
}

// Suppression des messages d'erreur lors de la saisie
document.querySelectorAll('#contact-form input, #contact-form textarea').forEach(input => {
    input.addEventListener('input', function() {
        clearFieldError(this);
    });
});

// Fonctions utilitaires
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showNotification(message, type = 'success') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Ajouter au DOM
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
    // Supprimer après 5 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

async function simulateFormSubmission() {
    return new Promise((resolve) => {
        setTimeout(resolve, 1000);
    });
}

// Effet de parallaxe pour le hero
window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero');
    if (hero) {
        const scrolled = window.pageYOffset;
        hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
    }
});

// État de l'authentification
let authState = {
    isLoggedIn: false,
    user: null,
    token: null
};

// Gestion de l'authentification
function toggleAuthMenu() {
    const menu = document.querySelector('.auth-menu');
    menu.classList.toggle('show');
}

// Fermer le menu quand on clique en dehors
document.addEventListener('click', (e) => {
    const menu = document.querySelector('.auth-menu');
    const profileCircle = document.querySelector('.profile-circle');
    
    if (!profileCircle.contains(e.target) && menu.classList.contains('show')) {
        menu.classList.remove('show');
    }
});

// Mise à jour des gestionnaires de formulaires
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire de soumission du formulaire de connexion
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');
            const rememberMe = document.getElementById('rememberMe')?.checked || false;

            // Validation des champs
            const emailValidation = validateField(email.value, 'email');
            const passwordValidation = validateField(password.value, 'password');

            // Afficher les erreurs si présentes
            if (!emailValidation.isValid) {
                showFieldError(email, emailValidation.message);
            }
            if (!passwordValidation.isValid) {
                showFieldError(password, passwordValidation.message);
            }

            // Si tout est valide, procéder à la connexion
            if (emailValidation.isValid && passwordValidation.isValid) {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent || 'Se connecter';
                
                try {
                    button.innerHTML = '<span class="loading-spinner"></span>';
                    button.disabled = true;

                    await loginUser({ email: email.value, password: password.value, rememberMe });
                    closeModal('login');
                } catch (error) {
                    console.error('Erreur de connexion:', error);
                    showNotification('Erreur de connexion', 'error');
                } finally {
                    button.textContent = originalText;
                    button.disabled = false;
                }
            }
        });
    }

    // Gestionnaire de soumission du formulaire d'inscription
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = document.getElementById('registerName');
            const email = document.getElementById('registerEmail');
            const password = document.getElementById('registerPassword');
            const confirmPassword = document.getElementById('confirmPassword');

            // Validation des champs
            const nameValidation = validateField(name.value, 'name');
            const emailValidation = validateField(email.value, 'email');
            const passwordValidation = validateField(password.value, 'password');
            const passwordsMatch = password.value === confirmPassword.value;

            // Afficher les erreurs si présentes
            if (!nameValidation.isValid) {
                showFieldError(name, nameValidation.message);
            }
            if (!emailValidation.isValid) {
                showFieldError(email, emailValidation.message);
            }
            if (!passwordValidation.isValid) {
                showFieldError(password, passwordValidation.message);
            }
            if (!passwordsMatch) {
                showFieldError(confirmPassword, ERROR_MESSAGES.password.match);
            }

            // Si tout est valide, procéder à l'inscription
            if (nameValidation.isValid && emailValidation.isValid && passwordValidation.isValid && passwordsMatch) {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent || "S'inscrire";
                
                try {
                    button.innerHTML = '<span class="loading-spinner"></span>';
                    button.disabled = true;

                    await registerUser({
                        name: name.value,
                        email: email.value,
                        password: password.value
                    });
                    closeModal('register');
                    openModal('login');
                } catch (error) {
                    console.error('Erreur d\'inscription:', error);
                    showNotification('Erreur lors de l\'inscription', 'error');
                } finally {
                    button.textContent = originalText;
                    button.disabled = false;
                }
            }
        });
    }
});

// Fonction de notification améliorée
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const icon = document.createElement('i');
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    const textSpan = document.createElement('span');
    textSpan.textContent = message;
    
    notification.appendChild(icon);
    notification.appendChild(textSpan);
    
    document.body.appendChild(notification);
    
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    });
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Gestion de l'authentification
function openModal(modalType) {
    // Fermer le menu auth
    document.querySelector('.auth-menu').classList.remove('show');
    
    // Ouvrir la modale appropriée
    const modal = document.getElementById(`${modalType}Modal`);
    modal.style.display = 'block';

    // Empêcher le scroll du body
    document.body.style.overflow = 'hidden';
}

function closeModal(modalType) {
    const modal = document.getElementById(`${modalType}Modal`);
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fermer la modale si on clique en dehors
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Toggle visibilité du mot de passe
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Expressions régulières pour la validation
const REGEX_PATTERNS = {
    name: /^[a-zA-ZÀ-ÿ\s]{3,50}$/,
    email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!.%*?&#_\-])[A-Za-z\d@$.!%*?&#_\-]{8,40}$/,
    phone: /^[\d\s+()-]{10,}$/,
    subject: /^.{3,100}$/,
    message: /^[\s\S]{10,1000}$/
};

// Messages d'erreur pour la validation
const ERROR_MESSAGES = {
    name: {
        required: 'Le nom est requis',
        pattern: 'Le nom doit contenir entre 3 et 50 caractères alphabétiques'
    },
    email: {
        required: 'L\'email est requis',
        pattern: 'Veuillez entrer une adresse email valide'
    },
    password: {
        required: 'Le mot de passe est requis',
        pattern: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
        match: 'Les mots de passe ne correspondent pas'
    },
    phone: {
        required: 'Le numéro de téléphone est requis',
        pattern: 'Veuillez entrer un numéro de téléphone valide'
    },
    subject: {
        required: 'Le sujet est requis',
        pattern: 'Le sujet doit contenir entre 3 et 100 caractères'
    },
    message: {
        required: 'Le message est requis',
        pattern: 'Le message doit contenir entre 10 et 1000 caractères'
    }
};

// Fonction de validation des champs
function validateField(value, type) {
    const validations = {
        email: {
            regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Email invalide'
        },
        password: {
            regex: /^.{8,}$/,
            message: 'Le mot de passe doit contenir au moins 8 caractères'
        }
    };

    if (!value) {
        return { isValid: false, message: 'Ce champ est requis' };
    }

    if (validations[type]) {
        return {
            isValid: validations[type].regex.test(value),
            message: validations[type].message
        };
    }

    return { isValid: true, message: '' };
}

// Fonction pour gérer le texte des boutons pendant le chargement
function handleButtonLoading(button, isLoading) {
    if (!button) return;
    
    if (isLoading) {
        button.dataset.originalText = button.textContent;
        button.textContent = 'Chargement...';
        button.disabled = true;
    } else {
        button.textContent = button.dataset.originalText || 'Envoyer';
        button.disabled = false;
    }
}

// Gestionnaire pour le formulaire d'inscription
document.getElementById('register-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const submitButton = form.querySelector('button[type="submit"]');

    try {
        const formData = new FormData(form);
        
        // Vérification des champs requis
        const email = formData.get('email');
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        const terms = formData.get('terms');
        const profilePhoto = formData.get('profile_photo');

        if (!email || !password || !confirmPassword) {
            throw new Error('Veuillez remplir tous les champs obligatoires');
        }

        if (password !== confirmPassword) {
            throw new Error('Les mots de passe ne correspondent pas');
        }

        if (!terms) {
            throw new Error('Veuillez accepter les conditions d\'utilisation');
        }

        handleButtonLoading(submitButton, true);

        const response = await fetch('auth/register.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            showNotification('Inscription réussie !', 'success');
            form.reset();
            closeModal('register');
            openModal('login');
        } else {
            throw new Error(result.error || 'Erreur lors de l\'inscription');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification(error.message, 'error');
    } finally {
        handleButtonLoading(submitButton, false);
    }
});

// Gestion de l'upload de photo de profil
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profilePhoto');
    const preview = document.getElementById('profilePreview');
    const uploadBtn = document.querySelector('.upload-btn');

    if (fileInput && preview && uploadBtn) {
    // Clic sur le bouton déclenche l'input file
    uploadBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // Gestion du changement de fichier
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
                // Vérification de la taille
                if (file.size > 5 * 1024 * 1024) {
                showNotification('La photo ne doit pas dépasser 5MB', 'error');
                return;
            }

                // Vérification du type
            if (!file.type.startsWith('image/')) {
                showNotification('Veuillez choisir une image', 'error');
                return;
            }

                // Prévisualisation
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                    preview.style.animation = 'fadeIn 0.3s ease-in-out';
            };
            reader.readAsDataURL(file);
        }
    });
    }
});

// Mise à jour de la fonction registerUser pour inclure la photo
async function registerUser(userData) {
    try {
        // Simuler un appel API
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Si une photo est fournie, on simule son upload
        if (userData.photo) {
            // Dans une vraie application, on enverrait la photo au serveur
            // Ici on crée juste une URL locale pour la démo
            userData.avatar = URL.createObjectURL(userData.photo);
        }
        
        showNotification('Inscription réussie ! Vous pouvez maintenant vous connecter.', 'success');
        return true;
    } catch (error) {
        showNotification('Erreur lors de l\'inscription', 'error');
        throw error;
    }
}

// Gestionnaire pour le formulaire de contact
document.getElementById('contact-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const submitButton = form.querySelector('button[type="submit"]');
    
    try {
        handleButtonLoading(submitButton, true);
        
        const formData = new FormData(form);
        const response = await fetch('contact/send.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            showNotification('Message envoyé avec succès !', 'success');
            form.reset();
        } else {
            throw new Error(result.error || 'Erreur lors de l\'envoi du message');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification(error.message, 'error');
    } finally {
        handleButtonLoading(submitButton, false);
    }
});

// Gestionnaire pour le formulaire de connexion
document.getElementById('login-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const submitButton = form.querySelector('button[type="submit"]');

    try {
        handleButtonLoading(submitButton, true);
        
        const formData = {
            email: form.querySelector('[name="email"]').value,
            password: form.querySelector('[name="password"]').value,
            rememberMe: form.querySelector('[name="remember-me"]')?.checked || false
        };

        await loginUser(formData);
        closeModal('login');
        form.reset();
    } catch (error) {
        console.error('Erreur:', error);
    } finally {
        handleButtonLoading(submitButton, false);
    }
});

// Prévisualisation de la photo de profil
document.getElementById('profilePhoto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('profilePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = 'images/default-avatar.webp';
    }
});

// Gestion du thème
function initTheme() {
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const savedTheme = localStorage.getItem('theme');
    
    // Définir le thème initial
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        } else {
        const initialTheme = prefersDarkScheme.matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', initialTheme);
        localStorage.setItem('theme', initialTheme);
    }
    
    // Mettre à jour l'icône
    updateThemeIcon(document.documentElement.getAttribute('data-theme'));
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    console.log('Theme changed to:', newTheme); // Pour le débogage
}

function updateThemeIcon(theme) {
    const icon = document.querySelector('.theme-toggle i');
    if (icon) {
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le thème
    initTheme();
    
    // Gestionnaire du bouton de thème
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    // Menu hamburger
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });

        // Fermer le menu quand on clique sur un lien
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Fermer le menu quand on clique en dehors
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && 
                !navMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
});
