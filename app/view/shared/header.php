<?php 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mundialist - Infografías</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="main-header">
    <div class="nav-container">
        <a href="index.php?action=mundiales" class="logo">
            Mundial<span>Infog</span>
        </a>

        <nav class="nav-menu">
            
            
            <?php if(isset($_SESSION['user'])): ?>
                <div class="user-info">
                    <a href="index.php?action=perfil" class="profile-link">
                        <?php if($_SESSION['user']['foto']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['user']['foto']); ?>" class="nav-avatar">
                        <?php else: ?>
                            <div class="nav-avatar-placeholder"><?php echo substr($_SESSION['user']['nombre'], 0, 1); ?></div>
                        <?php endif; ?>
                        <span><?php echo $_SESSION['user']['nombre']; ?></span>
                    </a>
                    <a href="index.php?action=logout" class="btn-logout-icon">🚪</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="index.php?action=login" class="link-login">Entrar</a>
                    <a href="index.php?action=register" class="btn-register-nav">Unirse</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="page-content">