<?php
/**
 * WPCode snippet: "SEO meta descriptions + structured data" (snippet #207)
 * Site: mindtherapyny.wpcomstaging.com
 * Outputs per-page <meta name="description"> tags plus JSON-LD:
 *  - MedicalBusiness schema on the front page
 *  - FAQPage schema on /faq/ (parsed live from the page's own Q&A content)
 *  - Person schema on each therapist page
 */

add_action( 'wp_head', function () {
    $descs = array(
        'our-services' => 'Individual, couples, teen, and substance use counseling by secure video across New York State. In-network with major insurance plans.',
        'our-specialties' => 'Anxiety, depression, trauma and PTSD, eating disorders, substance use, and burnout, treated by licensed New York clinicians who specialize in each.',
        'our-therapists' => 'Meet Mind Therapy: 13 licensed New York therapists directed by Ally Nektalov, LCSW, Master CASAC. Online sessions across New York State.',
        'about' => 'Mind Therapy is a New York telehealth practice founded by Ally Nektalov, LCSW. Thirteen licensed clinicians providing affordable, in-network psychotherapy.',
        'book' => 'Book online therapy in New York. Pick a day and time, request your appointment, and we verify your insurance before your first session.',
        'blog' => 'Notes on therapy, mental health, and getting better, from the clinicians at Mind Therapy in New York.',
        'faq' => 'Answers about insurance, cost, effectiveness of online therapy, scheduling, and switching therapists at Mind Therapy New York.',
        'how-online-therapy-works' => 'How online therapy works at Mind Therapy: book online, get matched with a licensed New York therapist, and meet by secure video, often within a week.',
        'careers' => 'Join Mind Therapy, a New York telehealth practice hiring licensed clinicians who want protected supervision, fair caseloads, and mission-driven colleagues.',
        'contact' => 'Contact Mind Therapy, online therapy across New York State. Email info@mindtherapyny.com or book a session online.'
    );
    $ther = array(
        'ally-nektalov' => array( 'Ally Nektalov', 'LCSW, Master CASAC - Director & Founder' ),
        'kameeka-burke' => array( 'Kameeka Burke', 'MHC-P' ),
        'elizabeth-han' => array( 'Elizabeth Han', 'MHC-P' ),
        'bryonna-perperian' => array( 'Bryonna Perperian', 'MHC-P' ),
        'abigail-harrison' => array( 'Abigail Harrison', 'MHC-P' ),
        'tamlyn-freedman' => array( 'Tamlyn Freedman', 'LMSW' ),
        'constance-grey' => array( 'Constance Grey', 'LCSW' ),
        'barbara-hoffmann' => array( 'Barbara Hoffmann', 'LCSW-R, PhD, CASAC' ),
        'sophia-alan' => array( 'Sophia Alan', 'LCSW' ),
        'maria-cicchilli' => array( 'Maria Cicchilli', 'LMHC, PhD' ),
        'sarah-kitt' => array( 'Sarah Kitt', 'LMSW' ),
        'bianca-e-ortiz' => array( 'Bianca E. Ortiz', 'LMHC' ),
        'alessandra-scalia' => array( 'Alessandra Scalia', 'LMHC, NCC, CASAC' )
    );
    $home_desc = 'In-network online therapy across New York State. Licensed therapists for individuals, couples, and teens, matched to you. Most clients pay only a copay.';
    $desc = '';
    $slug = is_singular() ? get_post_field( 'post_name', get_queried_object_id() ) : '';
    if ( is_front_page() ) { $desc = $home_desc; }
    elseif ( isset( $descs[ $slug ] ) ) { $desc = $descs[ $slug ]; }
    elseif ( isset( $ther[ $slug ] ) ) { $desc = $ther[ $slug ][0] . ', ' . $ther[ $slug ][1] . '. Online therapy in New York with Mind Therapy. In-network with major plans. Book a session today.'; }
    if ( $desc ) { echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . chr(10); }

    if ( is_front_page() ) {
        $biz = array(
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => 'Mind Therapy',
            'url' => home_url( '/' ),
            'email' => 'info@mindtherapyny.com',
            'description' => $home_desc,
            'areaServed' => array( '@type' => 'State', 'name' => 'New York' ),
            'medicalSpecialty' => 'Psychiatric',
            'priceRange' => '$$',
            'founder' => array( '@type' => 'Person', 'name' => 'Ally Nektalov', 'jobTitle' => 'Director and Founder, LCSW, Master CASAC' )
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $biz ) . '</script>' . chr(10);
    }

    if ( 'faq' === $slug ) {
        $content = get_post_field( 'post_content', get_queried_object_id() );
        if ( preg_match_all( '#<summary>(.*?)</summary>.*?<p>(.*?)</p>#s', $content, $mm ) ) {
            $items = array();
            foreach ( $mm[1] as $i => $q ) {
                $items[] = array(
                    '@type' => 'Question',
                    'name' => wp_strip_all_tags( $q ),
                    'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $mm[2][ $i ] ) )
                );
            }
            if ( $items ) {
                $faq = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items );
                echo '<script type="application/ld+json">' . wp_json_encode( $faq ) . '</script>' . chr(10);
            }
        }
    }

    if ( isset( $ther[ $slug ] ) ) {
        $p = array(
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $ther[ $slug ][0],
            'jobTitle' => $ther[ $slug ][1],
            'url' => get_permalink(),
            'worksFor' => array( '@type' => 'MedicalBusiness', 'name' => 'Mind Therapy', 'url' => home_url( '/' ) )
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $p ) . '</script>' . chr(10);
    }
}, 5 );
