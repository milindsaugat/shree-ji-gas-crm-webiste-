<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

$pdo = Database::get();

$legalPages = [
    [
        'slug' => 'terms',
        'title' => 'Terms & Conditions of Gas Supply & Equipment Services',
        'excerpt' => 'Official terms governing cylinder allocation, equipment sourcing, clinical safety protocols, and commercial transactions with Shree Ji Gas Industries.',
        'content' => '<h2>1. Scope of Supply & Engagement</h2>
<p>These terms and conditions govern all equipment purchases, medical and industrial gas cylinder allocations, refilling arrangements, and manifold technical services provided by <strong>Shree Ji Gas Industries</strong> (registered office: 33/103, Pratappura, Agra, Uttar Pradesh).</p>
<p>By placing an enquiry, issuing a purchase order, or accepting cylinder delivery, healthcare institutions, clinics, and industrial clients agree to be bound by these operating terms.</p>

<h2>2. Medical Gas Purity & Statutory Standards</h2>
<p>All medical oxygen cylinders supplied comply with the standards prescribed under the <em>Drugs & Cosmetics Act</em> and <em>Indian Pharmacopoeia (IP)</em> standards for medical-grade oxygen (minimum 99.5% purity). Every cylinder undergoes valve inspection, pressure validation, and statutory tare-weight verification before dispatch.</p>

<h2>3. Cylinder Handling, Ownership & Security Deposit</h2>
<ul>
    <li><strong>Cylinder Ownership:</strong> Unless explicitly purchased as outright equipment, all high-pressure gas cylinders remain the sole property of Shree Ji Gas Industries.</li>
    <li><strong>Holding Period:</strong> Cylinders are provided on a refundable security deposit or rental allocation basis for clinical rotation.</li>
    <li><strong>Return of Empties:</strong> Empty cylinders must be returned in sound physical condition, with valves completely closed and protective safety caps intact.</li>
    <li><strong>Damage or Loss:</strong> Any physical damage to valves, spindle rounding, contamination, or total loss of cylinder shell will result in forfeiture of deposit or invoice at prevailing replacement costs.</li>
</ul>

<h2>4. Clinical Application & Medical Supervision</h2>
<p>Oxygen therapy and medical gas equipment must strictly be administered under the instruction and supervision of qualified medical practitioners. Shree Ji Gas Industries supplies certified equipment and gas volume but does not provide clinical diagnosis, patient prescription, or direct clinical treatment advice.</p>

<h2>5. Payment Terms & Invoicing</h2>
<p>Invoices are issued upon dispatch or contract cycle. Payment terms for institutional accounts are net 15 days unless otherwise agreed in writing. For retail and urgent emergency refills, payment is due upon delivery via approved UPI, NEFT, or bank transfer.</p>

<h2>6. Jurisdiction & Dispute Resolution</h2>
<p>Any disputes arising out of contracts, deliveries, or supply agreements with Shree Ji Gas Industries shall be subject to the exclusive jurisdiction of the competent courts in <strong>Agra, Uttar Pradesh</strong>.</p>',
        'thumbnail' => 'assets/images/hospital.jpg',
        'image_alt' => 'Shree Ji Gas Industries facility and equipment terms',
        'status' => 'published'
    ],
    [
        'slug' => 'compliance',
        'title' => 'Safety & Medical Gas Regulatory Compliance',
        'excerpt' => 'Our strict adherence to Petroleum & Explosives Safety Organisation (PESO) rules, Indian Pharmacopoeia standards, and hospital manifold safety guidelines.',
        'content' => '<h2>1. Regulatory Framework & Statutory Approvals</h2>
<p>High-pressure medical and industrial gases require rigorous regulatory governance. Shree Ji Gas Industries operates in strict compliance with the following statutory frameworks:</p>
<ul>
    <li><strong>Gas Cylinders Rules, 2016</strong> administered by the <em>Petroleum and Explosives Safety Organisation (PESO)</em>, Ministry of Commerce and Industry, Government of India.</li>
    <li><strong>Static & Mobile Pressure Vessels (SMPV) Rules</strong> for bulk storage and manifold decanting safety.</li>
    <li><strong>Indian Pharmacopoeia (IP) / Bureau of Indian Standards (BIS)</strong> specifications for medical gases and valve fittings (IS:3224).</li>
</ul>

<h2>2. Mandatory Hydro-Static Testing (5-Year Cycle)</h2>
<p>Under PESO mandate, every seamless steel cylinder must undergo statutory hydrostatic stretch testing every five (5) years at an authorized testing station. Shree Ji Gas Industries maintains an active digital hydro-testing register:</p>
<ul>
    <li>Every dispatched cylinder features a clear neck-ring stamp showing the last test date and due expiry date.</li>
    <li>Cylinders due for hydro-testing are systematically quarantined and routed to testing facilities before any refilling cycle.</li>
    <li>Batch test certificates and burst-pressure compliance test reports are available on request for institutional clinical audits.</li>
</ul>

<h2>3. Standard Colour Coding & Pin-Index Compatibility</h2>
<p>To eliminate clinical hazard or cross-contamination risk, all cylinders strictly adhere to statutory colour coding:</p>
<ul>
    <li><strong>Medical Oxygen:</strong> Black body with brilliant White shoulder.</li>
    <li><strong>Nitrous Oxide:</strong> French Blue body and shoulder.</li>
    <li><strong>Carbon Dioxide:</strong> Black body with Silver shoulder.</li>
    <li><strong>Medical Air:</strong> French Grey body with White and Black quartered shoulder.</li>
</ul>
<p>Pin-index and valve outlets conform strictly to IS:3224 standards to prevent incorrect gas regulator coupling.</p>

<h2>4. Emergency Hospital Manifold Protocols</h2>
<p>For healthcare institutions with central pipeline manifold installations, our technical safety team provides scheduled leak audits, dual-bank changeover switch inspections, line pressure drop assessments, and emergency buffer storage planning.</p>',
        'thumbnail' => 'assets/images/manifold.jpg',
        'image_alt' => 'Central medical gas manifold regulatory safety installation',
        'status' => 'published'
    ],
    [
        'slug' => 'shipping-policy',
        'title' => 'Emergency Delivery, Safe Transport & Cylinder Deposit Policy',
        'excerpt' => 'Fast-response hospital oxygen delivery across Agra & Western UP, statutory hazardous cargo logistics, and transparent cylinder return guidelines.',
        'content' => '<h2>1. 24/7 Priority Emergency Hospital Dispatch</h2>
<p>Critical care healthcare cannot wait. Shree Ji Gas Industries maintains a dedicated <strong>24/7 Priority Dispatch Desk</strong> for hospitals, ICUs, and nursing homes across Agra, Mathura, Firozabad, and Western Uttar Pradesh:</p>
<ul>
    <li><strong>Emergency Hospital Deliveries:</strong> Dispatched within 45 to 90 minutes of receiving distress alert or ward requisition.</li>
    <li><strong>Scheduled Ward Replenishments:</strong> Daily morning and evening scheduled delivery routes for contracted facilities.</li>
    <li><strong>Home Care Support:</strong> Same-day doorstep delivery across Agra municipal limits for doctor-prescribed patient setups.</li>
</ul>

<h2>2. Hazardous Material Transport Protocols</h2>
<p>Compressed gas cylinders are classified as hazardous cargo. All Shree Ji Gas transport vehicles adhere to strict transport safety norms:</p>
<ul>
    <li>Vehicles are well-ventilated, equipped with certified fire suppression extinguishers, and operated by HAZMAT-trained drivers.</li>
    <li>Cylinders are transported upright, securely chained or slotted in custom delivery racks to prevent collision during transit.</li>
    <li>Valves remain fitted with protective screw caps throughout the journey until handover at the hospital manifold room.</li>
</ul>

<h2>3. Handover & Delivery Verification Protocol</h2>
<p>Upon delivery, the receiving hospital biomedical engineer or facility representative must inspect:</p>
<ul>
    <li>Valve seal and tamper-evident cap integrity.</li>
    <li>Static pressure reading on the test regulator gauge.</li>
    <li>Tare weight vs. filled weight on delivery challan.</li>
</ul>

<h2>4. Cylinder Security Deposit & Refund Process</h2>
<p>For institutions and individuals utilizing our rotating cylinder stock:</p>
<ul>
    <li>A refundable security deposit is collected per cylinder at the time of initial issue.</li>
    <li>Upon conclusion of supply requirement and return of sound, undamaged cylinder shell with original valve intact, 100% of the security deposit is refunded within 2 business days via original payment mode or bank transfer.</li>
    <li>A digital return receipt is issued immediately upon physical pickup by our delivery crew.</li>
</ul>',
        'thumbnail' => 'assets/images/homecare-oxygen.jpg',
        'image_alt' => 'Shree Ji Gas delivery and mobile cylinder dispatch',
        'status' => 'published'
    ]
];

$stmtCheck = $pdo->prepare('SELECT id FROM pages WHERE slug = ?');
$stmtInsert = $pdo->prepare('INSERT INTO pages (title, slug, excerpt, content, thumbnail, image_alt, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmtUpdate = $pdo->prepare('UPDATE pages SET title = ?, excerpt = ?, content = ?, thumbnail = ?, image_alt = ?, status = ? WHERE slug = ?');

foreach ($legalPages as $page) {
    $stmtCheck->execute([$page['slug']]);
    $existingId = $stmtCheck->fetchColumn();
    if ($existingId) {
        $stmtUpdate->execute([$page['title'], $page['excerpt'], $page['content'], $page['thumbnail'], $page['image_alt'], $page['status'], $page['slug']]);
        echo "Updated page: {$page['slug']} (ID: $existingId)\n";
    } else {
        $stmtInsert->execute([$page['title'], $page['slug'], $page['excerpt'], $page['content'], $page['thumbnail'], $page['image_alt'], $page['status']]);
        $newId = $pdo->lastInsertId();
        echo "Inserted page: {$page['slug']} (ID: $newId)\n";
    }

    // Also register corresponding SEO meta entries if missing
    $seoStmt = $pdo->prepare('SELECT id FROM seo_meta WHERE page_key = ?');
    $seoStmt->execute(['/' . $page['slug']]);
    if (!$seoStmt->fetchColumn()) {
        $seoInsert = $pdo->prepare('INSERT INTO seo_meta (page_key, meta_title, meta_desc, focus_keyword, og_title, og_desc, og_image) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $seoInsert->execute([
            '/' . $page['slug'],
            $page['title'] . ' | Shree Ji Gas Industries',
            $page['excerpt'],
            str_replace('-', ' ', $page['slug']) . ', medical gas Agra, Shree Ji Gas',
            $page['title'],
            $page['excerpt'],
            $page['thumbnail']
        ]);
        echo "Registered SEO meta for /{$page['slug']}\n";
    }
}

echo "All legal pages successfully synchronized in database.\n";
