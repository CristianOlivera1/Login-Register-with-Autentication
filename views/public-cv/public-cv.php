<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['user_id']);

if (!isset($cv) || !$cv['is_public']) {
    http_response_code(404);
    ob_start();
    include __DIR__ . '/../404.php';
    $content = ob_get_clean();

    include __DIR__ . '/../layouts/main.php';
    exit;
}

$isSuggested = isset($_GET['suggested']) && $_GET['suggested'] == '1';
?>

<?php if ($isSuggested): ?>
    <div id="suggestedNotification" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-blue-500/90 backdrop-blur-sm text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3 transition-all duration-300">
        <iconify-icon icon="solar:info-circle-linear" width="20"></iconify-icon>
        <span class="text-sm">El CV solicitado no existe. Te mostramos este CV popular en su lugar.</span>
        <button onclick="this.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.remove(), 300)"
            class="ml-2 hover:bg-white/20 rounded p-1 transition-colors">
            <iconify-icon icon="line-md:close-small" width="16"></iconify-icon>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.getElementById('suggestedNotification');
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, 8000);
    </script>
<?php endif; ?>

<main class="py-4 md:py-8">
    <div class="max-w-4xl mx-auto px-3 md:px-4 mt-14 sm:mt-6">
        <div class="a4-paper mx-auto p-4 md:p-8 lg:p-12 bg-white shadow-lg">
            <?php
            $cvData = json_decode($cv['cv_data'], true);
            $basics = $cvData['basics'] ?? [];
            $work = $cvData['work'] ?? [];
            $projects = $cvData['projects'] ?? [];
            $education = $cvData['education'] ?? [];
            $skills = $cvData['skills'] ?? [];
            $languages = $cvData['languages'] ?? [];

            // Verificar el tipo de plantilla
            $templateName = $cv['template_name'] ?? '';
            $isHarvardTemplate = stripos($templateName, 'harvard') !== false;
            $isModernChronological = stripos($templateName, 'modern chronological') !== false ||
                stripos($templateName, 'chronological') !== false ||
                stripos($templateName, 'platinum') !== false;
            ?>

            <?php if ($isModernChronological): ?>
                <!-- Plantilla Platinum -->
                <style>
                    .cv-body {
                        font-family: 'Inter', sans-serif;
                        line-height: 1.5;
                        color: #1a202c;
                    }

                    .section-header {
                        display: flex;
                        align-items: center;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        font-weight: 700;
                        font-size: 10pt;
                        color: #2d3748;
                        border-bottom: 1px solid #e2e8f0;
                        margin-bottom: 12px;
                        margin-top: 20px;
                        padding-bottom: 4px;
                    }
                </style>

                <div class="cv-body p-1 md:p-2">
                    <!-- Header Minimalista -->
                    <header class="text-center mb-6 md:mb-8">
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-tighter text-slate-900 mb-2 md:mb-3">
                            <?php echo htmlspecialchars($basics['name']); ?>
                        </h1>
                        <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-2 sm:gap-x-4 text-[8pt] sm:text-[9.5pt] text-slate-600 font-medium">
                            <?php if (!empty($basics['phone'])): ?>
                                <span><?php echo htmlspecialchars($basics['phone']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($basics['phone']) && !empty($basics['email'])): ?>
                                <span class="text-slate-300 hidden sm:inline">|</span>
                            <?php endif; ?>
                            <?php if (!empty($basics['email'])): ?>
                                <span><?php echo htmlspecialchars($basics['email']); ?></span>
                            <?php endif; ?>
                            <?php if ((!empty($basics['phone']) || !empty($basics['email'])) && !empty($basics['location'])): ?>

                                <span class="text-slate-300 hidden sm:inline">|</span>
                            <?php endif; ?>
                            <?php if (!empty($basics['location'])): ?>
                                <span><?php echo htmlspecialchars($basics['location']['city'] ?? $basics['location']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($basics['url'])): ?>
                                <span class="text-slate-300 hidden sm:inline">|</span>
                                <a href="<?php echo $basics['url']; ?>" class="text-slate-900 underline decoration-slate-300 block sm:inline mt-1 sm:mt-0">Portafolio</a>
                            <?php endif; ?>
                        </div>
                    </header>

                    <!-- Resumen Ejecutivo -->
                    <?php if (!empty($basics['summary'])): ?>
                        <section>
                            <h2 class="section-header">Perfil Profesional</h2>
                            <p class="text-[10pt] text-slate-700 text-justify">
                                <?php echo nl2br(htmlspecialchars($basics['summary'])); ?>
                            </p>
                        </section>
                    <?php endif; ?>

                    <!-- Experiencia: El núcleo del CV -->
                    <?php if (!empty($work)): ?>
                        <section>
                            <h2 class="section-header">Experiencia Profesional</h2>
                            <?php foreach ($work as $job): ?>
                                <div class="mb-4 md:mb-5">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1 gap-1">
                                        <h3 class="text-[10pt] md:text-[11pt] font-bold text-slate-900"><?php echo htmlspecialchars($job['name']); ?></h3>
                                        <span class="text-[8pt] md:text-[9pt] font-semibold text-slate-500"><?php echo htmlspecialchars(formatDateRange($job['startDate'], $job['endDate'])); ?></span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-2 gap-1">
                                        <span class="text-[9pt] md:text-[10pt] font-medium text-slate-700 italic"><?php echo htmlspecialchars($job['position']); ?></span>
                                        <span class="text-[8pt] md:text-[9pt] text-slate-400"><?php echo htmlspecialchars($job['location'] ?? ''); ?></span>
                                    </div>
                                    <?php if (!empty($job['highlights'])): ?>
                                        <ul class="list-disc ml-3 md:ml-4 space-y-1">
                                            <?php foreach ($job['highlights'] as $h): ?>
                                                <li class="text-[8.5pt] md:text-[9.5pt] text-slate-700 pl-1"><?php echo htmlspecialchars($h); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Habilidades: Agrupadas para lectura rápida -->
                    <?php if (!empty($skills)): ?>
                        <section>
                            <h2 class="section-header">Habilidades y Competencias</h2>
                            <div class="grid grid-cols-1 gap-2">
                                <?php foreach ($skills as $skill): ?>
                                    <p class="text-[9.5pt]">
                                        <span class="font-bold text-slate-800"><?php echo htmlspecialchars($skill['name']); ?>:</span>
                                        <span class="text-slate-600"><?php echo htmlspecialchars(implode(', ', $skill['keywords'] ?? [])); ?></span>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- Educación -->
                    <?php if (!empty($education)): ?>
                        <section>
                            <h2 class="section-header">Formación Académica</h2>
                            <?php foreach ($education as $edu): ?>
                                <div class="flex justify-between items-baseline mb-1">
                                    <h3 class="text-[10pt] font-bold text-slate-900"><?php echo htmlspecialchars($edu['institution']); ?></h3>
                                    <span class="text-[9pt] text-slate-500"><?php echo htmlspecialchars(formatDateRange($edu['startDate'], $edu['endDate'])); ?></span>
                                </div>
                                <p class="text-[9.5pt] text-slate-700">
                                    <?php
                                    $degree = $edu['studyType'] ?? '';
                                    $area = $edu['area'] ?? $edu['field'] ?? '';
                                    echo htmlspecialchars($degree . ($area ? " en " . $area : ""));
                                    ?>
                                </p>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>
                </div>
            <?php elseif ($isHarvardTemplate): ?>
                <!-- Plantilla Harvard Professional -->
                <style>
                    .font-stix {
                        font-family: 'STIX Two Text', serif;
                    }
                </style>

                <div class="font-stix text-slate-900">
                    <!-- Header -->
                    <header class="text-center mb-2 md:mb-3">
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight mb-1 md:mb-2"><?php echo htmlspecialchars($basics['name'] ?? 'Nombre Completo'); ?></h1>
                        <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-1 sm:gap-2 text-[9pt] md:text-[11pt] text-slate-700">
                            <?php if (!empty($basics['location'])): ?>
                                <span>
                                    <?php
                                    if (is_array($basics['location'])) {
                                        echo htmlspecialchars(($basics['location']['city'] ?? '') . ', ' . ($basics['location']['region'] ?? ''));
                                    } else {
                                        echo htmlspecialchars($basics['location']);
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($basics['location']) && !empty($basics['url'])): ?>
                                <span class="text-slate-500 hidden sm:inline">•</span>
                            <?php endif; ?>
                            <?php if (!empty($basics['url'])): ?>
                                <a href="<?php echo htmlspecialchars($basics['url']); ?>" target="_blank" class="text-blue-600">
                                    <?php echo htmlspecialchars(preg_replace('/^https?:\/\//', '', $basics['url'])); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ((!empty($basics['location']) || !empty($basics['url'])) && !empty($basics['phone'])): ?>
                                <span class="text-slate-500 hidden sm:inline">•</span>
                            <?php endif; ?>
                            <?php if (!empty($basics['phone'])): ?>
                                <span><?php echo htmlspecialchars($basics['phone']); ?></span>
                            <?php endif; ?>
                            <?php if (((!empty($basics['location']) || !empty($basics['url']) || !empty($basics['phone'])) && !empty($basics['email']))): ?>
                                <span class="text-slate-500 hidden sm:inline">•</span>
                            <?php endif; ?>
                            <?php if (!empty($basics['email'])): ?>
                                <span><?php echo htmlspecialchars($basics['email']); ?></span>
                            <?php endif; ?>
                        </div>
                        <hr class="mt-1 border-slate-300 border-b">
                    </header>

                    <!-- Summary -->
                    <?php if (!empty($basics['summary'])): ?>
                        <section class="mb-3">
                            <p class="text-[11pt] text-slate-800 italic leading-snug">
                                <?php echo nl2br(htmlspecialchars($basics['summary'])); ?>
                            </p>
                        </section>
                    <?php endif; ?>

                    <!-- Experiencia Laboral -->
                    <?php if (!empty($work)): ?>
                        <section class="mb-3">
                            <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Experiencia Profesional</h2>
                            <?php foreach ($work as $index => $job): ?>
                                <div class="<?php echo ($index < count($work) - 1) ? 'mb-5' : ''; ?>">
                                    <div class="flex justify-between items-baseline">
                                        <h3 class="font-bold text-[12pt]"><?php echo htmlspecialchars($job['name'] ?? 'Empresa'); ?></h3>
                                        <span class="font-bold text-[11pt]"><?php echo htmlspecialchars($job['location'] ?? ''); ?></span>
                                    </div>
                                    <div class="flex justify-between items-baseline mb-2">
                                        <span class="text-[11pt]"><?php echo htmlspecialchars($job['position'] ?? 'Cargo'); ?></span>
                                        <span class="italic text-[10pt]"><?php echo htmlspecialchars(formatDateRange($job['startDate'] ?? '', $job['endDate'] ?? '')); ?></span>
                                    </div>
                                    <?php if (!empty($job['highlights'])): ?>
                                        <ul class="list-disc list-outside ml-5 space-y-0.5 text-[10.5pt] text-slate-800">
                                            <?php foreach ($job['highlights'] as $highlight): ?>
                                                <li><?php echo htmlspecialchars($highlight); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Proyectos -->
                    <?php if (!empty($projects)): ?>
                        <section class="mb-3">
                            <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Proyectos</h2>
                            <?php foreach ($projects as $index => $project): ?>
                                <div class="<?php echo ($index < count($projects) - 1) ? 'mb-5' : ''; ?>">
                                    <div class="flex justify-between items-baseline">
                                        <h3 class="font-bold text-[12pt]"><?php echo htmlspecialchars($project['name'] ?? 'Proyecto'); ?></h3>
                                        <span class="font-bold text-[11pt]"><?php echo htmlspecialchars($project['type'] ?? 'Proyecto Personal'); ?></span>
                                    </div>
                                    <div class="flex justify-between items-baseline mb-2">
                                        <span class="text-[11pt]"><?php echo htmlspecialchars($project['role'] ?? 'Desarrollador'); ?></span>
                                        <span class="italic text-[10pt]"><?php echo htmlspecialchars(formatDateRange($project['startDate'] ?? '', $project['endDate'] ?? '')); ?></span>
                                    </div>
                                    <?php if (!empty($project['highlights'])): ?>
                                        <ul class="list-disc list-outside ml-5 space-y-0.5 text-[10.5pt] text-slate-800">
                                            <?php foreach ($project['highlights'] as $highlight): ?>
                                                <li><?php echo htmlspecialchars($highlight); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php if (!empty($project['technologies'])): ?>
                                        <p class="text-[10pt] mt-1">
                                            <span class="italic">Tecnologías:</span> <?php echo htmlspecialchars(implode(', ', $project['technologies'])); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Educación -->
                    <?php if (!empty($education)): ?>
                        <section class="mb-3">
                            <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Educación</h2>
                            <?php foreach ($education as $edu): ?>
                                <div class="flex justify-between items-baseline">
                                    <h3 class="font-bold text-[12pt]"><?php echo htmlspecialchars($edu['institution'] ?? 'Universidad'); ?></h3>
                                    <span class="font-bold text-[11pt]"><?php echo htmlspecialchars($edu['location'] ?? ''); ?></span>
                                </div>
                                <div class="flex justify-between items-baseline">
                                    <span class="text-[11pt]"><?php echo htmlspecialchars(($edu['studyType'] ?? '') . (!empty($edu['area']) ? ' en ' . $edu['area'] : '')); ?></span>
                                    <span class="italic text-[10pt]"><?php echo htmlspecialchars(formatDateRange($edu['startDate'] ?? '', $edu['endDate'] ?? '')); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Skills -->
                    <?php if (!empty($skills)): ?>
                        <section>
                            <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Skills Adicionales</h2>
                            <ul class="list-disc list-outside ml-5 space-y-1 text-[11pt] text-slate-800">
                                <?php foreach ($skills as $skillGroup): ?>
                                    <li><?php echo htmlspecialchars((!empty($skillGroup['keywords']) ? implode(', ', $skillGroup['keywords']) : $skillGroup['name'])); ?></li>
                                <?php endforeach; ?>
                                <?php if (!empty($languages)): ?>
                                    <li>Idiomas: <?php
                                                    $langTexts = [];
                                                    foreach ($languages as $lang) {
                                                        $langTexts[] = htmlspecialchars($lang['language'] . ' (' . ($lang['fluency'] ?? 'Intermedio') . ')');
                                                    }
                                                    echo implode(', ', $langTexts);
                                                    ?></li>
                                <?php endif; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Plantilla Estándar -->

                <header class="mb-6 md:mb-8 border-b border-slate-200 pb-4 md:pb-6 flex flex-col md:flex-row md:items-start gap-4 md:gap-6">
                    <?php if (!empty($basics['image'])): ?>
                        <div class="flex-shrink-0 self-center md:self-start">
                            <img
                                src="<?php echo htmlspecialchars($basics['image']); ?>"
                                alt="<?php echo htmlspecialchars($basics['name'] ?? 'Foto de perfil'); ?>"
                                class="w-20 h-20 md:w-28 md:h-28 object-cover rounded-xl border border-slate-100 shadow-sm"
                                onerror="this.style.display='none'" />
                        </div>
                    <?php endif; ?>

                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-1">
                            <?php echo htmlspecialchars($basics['name'] ?? ''); ?>
                        </h1>

                        <?php if (!empty($basics['label'])): ?>
                            <p class="text-base md:text-lg text-slate-600 font-medium mb-3">
                                <?php echo htmlspecialchars($basics['label']); ?>
                            </p>
                        <?php endif; ?>

                        <div class="flex flex-col sm:flex-row sm:justify-center md:justify-start flex-wrap gap-2 sm:gap-x-4 gap-y-2 text-xs text-slate-500 font-medium">
                            <?php if (!empty($basics['location'])): ?>
                                <div class="flex items-center gap-1 justify-center md:justify-start">
                                    <iconify-icon icon="solar:map-point-linear" width="14" height="14"></iconify-icon>
                                    <?php
                                    if (is_array($basics['location'])) {
                                        echo htmlspecialchars(($basics['location']['city'] ?? '') . ', ' . ($basics['location']['region'] ?? ''));
                                    } else {
                                        echo htmlspecialchars($basics['location']);
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($basics['url'])): ?>
                                <a href="<?php echo htmlspecialchars($basics['url']); ?>" target="_blank" class="flex items-center gap-1 text-blue-600 justify-center md:justify-start">
                                    <iconify-icon icon="line-md:link" width="14" height="14"></iconify-icon>
                                    <?php echo htmlspecialchars(preg_replace('/^https?:\/\//', '', $basics['url'])); ?>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($basics['phone'])): ?>
                                <div class="flex items-center gap-1 justify-center md:justify-start">
                                    <iconify-icon icon="solar:phone-linear" width="14" height="14"></iconify-icon>
                                    <?php echo htmlspecialchars($basics['phone']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($basics['email'])): ?>
                                <div class="flex items-center gap-1 justify-center md:justify-start">
                                    <iconify-icon icon="solar:letter-linear" width="14" height="14"></iconify-icon>
                                    <?php echo htmlspecialchars($basics['email']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <?php if (!empty($basics['summary'])): ?>
                    <section class="mb-8">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($basics['summary'])); ?>
                        </p>
                    </section>
                <?php endif; ?>

                <?php if (!empty($work)): ?>
                    <section class="mb-6 md:mb-8">
                        <h2 class="text-xs md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 md:mb-4 border-b border-slate-100 pb-2">
                            Experiencia Profesional
                        </h2>
                        <?php foreach ($work as $job): ?>
                            <div class="mb-4 md:mb-6">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1 gap-1">
                                    <h3 class="font-bold text-slate-800 text-sm md:text-base"><?php echo htmlspecialchars($job['name'] ?? ''); ?></h3>
                                    <span class="text-xs text-slate-500 font-mono">
                                        <?php echo htmlspecialchars(formatDateRange($job['startDate'] ?? '', $job['endDate'] ?? '')); ?>
                                    </span>
                                </div>
                                <div class="text-xs md:text-sm text-slate-600 italic mb-2">
                                    <?php echo htmlspecialchars($job['position'] ?? ''); ?>
                                </div>
                                <?php if (!empty($job['location'])): ?>
                                    <div class="text-xs text-slate-500 mb-2">
                                        <?php echo htmlspecialchars($job['location']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($job['highlights'])): ?>
                                    <ul class="list-disc list-outside ml-3 md:ml-4 space-y-1 text-xs md:text-sm text-slate-600 leading-relaxed">
                                        <?php foreach ($job['highlights'] as $highlight): ?>
                                            <li><?php echo htmlspecialchars($highlight); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <?php if (!empty($projects)): ?>
                    <section class="mb-6 md:mb-8">
                        <h2 class="text-xs md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 md:mb-4 border-b border-slate-100 pb-2">
                            Proyectos
                        </h2>
                        <?php foreach ($projects as $project): ?>
                            <div class="mb-4 md:mb-6">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1 gap-1">
                                    <h3 class="font-bold text-slate-800 text-sm md:text-base"><?php echo htmlspecialchars($project['name'] ?? ''); ?></h3>
                                    <span class="text-xs text-slate-500 font-mono">
                                        <?php echo htmlspecialchars(formatDateRange($project['startDate'] ?? '', $project['endDate'] ?? '')); ?>
                                    </span>
                                </div>
                                <?php if (!empty($project['role'])): ?>
                                    <div class="text-xs md:text-sm text-slate-600 italic mb-1"><?php echo htmlspecialchars($project['role']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($project['type'])): ?>
                                    <div class="text-xs text-slate-500 mb-2"><?php echo htmlspecialchars($project['type']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($project['description'])): ?>
                                    <p class="text-xs md:text-sm text-slate-600 mb-2"><?php echo htmlspecialchars($project['description']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($project['highlights'])): ?>
                                    <ul class="list-disc list-outside ml-3 md:ml-4 space-y-1 text-xs md:text-sm text-slate-600 leading-relaxed">
                                        <?php foreach ($project['highlights'] as $highlight): ?>
                                            <li><?php echo htmlspecialchars($highlight); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php if (!empty($project['technologies'])): ?>
                                    <div class="mt-2">
                                        <span class="text-xs font-medium text-slate-700">Tecnologías: </span>
                                        <span class="text-xs text-slate-600"><?php echo htmlspecialchars(implode(', ', $project['technologies'])); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <?php if (!empty($education)): ?>
                    <section class="mb-6 md:mb-8">
                        <h2 class="text-xs md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 md:mb-4 border-b border-slate-100 pb-2">
                            Educación
                        </h2>
                        <?php foreach ($education as $edu): ?>
                            <div class="mb-3 md:mb-4">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1 gap-1">
                                    <h3 class="font-bold text-slate-800 text-sm md:text-base"><?php echo htmlspecialchars($edu['institution'] ?? ''); ?></h3>
                                    <span class="text-xs text-slate-500 font-mono">
                                        <?php echo htmlspecialchars(formatDateRange($edu['startDate'] ?? '', $edu['endDate'] ?? '')); ?>
                                    </span>
                                </div>
                                <?php if (!empty($edu['studyType'])): ?>
                                    <div class="text-xs md:text-sm text-slate-600"><?php echo htmlspecialchars($edu['studyType']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($edu['area'])): ?>
                                    <div class="text-xs md:text-sm text-slate-600"><?php echo htmlspecialchars($edu['area']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($edu['location'])): ?>
                                    <div class="text-xs text-slate-500"><?php echo htmlspecialchars($edu['location']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <?php if (!empty($skills)): ?>
                    <section class="mb-6 md:mb-8">
                        <h2 class="text-xs md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 md:mb-4 border-b border-slate-100 pb-2">
                            Habilidades
                        </h2>
                        <?php foreach ($skills as $skillGroup): ?>
                            <div class="mb-3">
                                <h4 class="text-xs md:text-sm font-semibold text-slate-800 mb-2"><?php echo htmlspecialchars($skillGroup['name'] ?? ''); ?></h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach (($skillGroup['keywords'] ?? []) as $skill): ?>
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-medium rounded">
                                            <?php echo htmlspecialchars($skill); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <?php if (!empty($languages)): ?>
                    <section>
                        <h2 class="text-xs md:text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 md:mb-4 border-b border-slate-100 pb-2">
                            Idiomas
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            <?php foreach ($languages as $lang): ?>
                                <div class="text-xs md:text-sm">
                                    <span class="font-medium text-slate-800"><?php echo htmlspecialchars($lang['language'] ?? ''); ?></span>
                                    <span class="text-slate-600"> - <?php echo htmlspecialchars($lang['fluency'] ?? ''); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            <?php endif; // Fin plantilla estándar 
            ?>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (img.src && !img.src.startsWith('http') && !img.src.startsWith('data:')) {
                if (img.src.startsWith('/')) {
                    img.src = window.location.origin + img.src;
                }
            }
        });
    });
</script>

<?php
function formatDateRange($startDate, $endDate)
{
    $formatDate = function ($dateStr) {
        if (!$dateStr) return '';
        try {
            $date = new DateTime($dateStr);
            return $date->format('M Y');
        } catch (Exception $e) {
            return $dateStr;
        }
    };

    $start = $formatDate($startDate);
    $end = $endDate ? $formatDate($endDate) : 'Actual';

    return $start && $end ? "$start - $end" : ($start ?: $end ?: '');
}
?>