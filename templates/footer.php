<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<footer>
<div class="container">
	<section id="about-us" class="column">
		<h3><?= __('About Us') ?></h3>
		<p><?= __('<strong>&gt; CTF</strong> is a platform for jeopardy style CTFs. Developed by <em>Niccolò Scatena</em> for the Web Development course of the University of Pisa.') ?> <a href="<?= $application->buildLink('about') ?>"><?= __('More...') ?></a></p>
	</section>
	<section id="social-links" class="column">
		<h3><?= __('Follow Us') ?></h3>
		<?php foreach ($application->config['social_names'] as $social => $pageName):
			if (empty($pageName)) continue; ?>
			<a href="<?= $application->buildExternalLink("www.$social.com/$pageName") ?>" target="_blank" rel="external">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMidYMid meet">
					<use xlink:href="#social-def-<?= $social ?>"></use>
				</svg>
			</a>
		<?php endforeach; ?>
	</section>
	<section id="language-selector" class="column">
		<h3><?= __('Language') ?></h3>
		<?php foreach ($application->config['selector_languages'] as $lang): ?>
			<svg id="flag-<?= $lang ?>" width="20" height="15" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
				<use xlink:href="#flag-def-<?= $lang ?>"></use>
			</svg>
		<?php endforeach; ?>
	</section>
</div>
	<p id="copyright"><?= __('&copy; Copyright 2019 - Niccolò Scatena (University of Pisa) - All Rights Reserved.') ?></p>
	<svg display="none">
		<defs>
			<g id="social-def-facebook" xmlns="http://www.w3.org/2000/svg">
				<path d="M6 9H3V6h3V4c0-2.7 1.672-4 4.08-4 1.153 0 2.144.086 2.433.124v2.821h-1.67c-1.31 0-1.563.623-1.563 1.536V6H13l-1 3H9.28v7H6.023L6 9z" fill-rule="evenodd"></path>
			</g>
			<g id="social-def-instagram" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 16H4c-2.056 0-4-1.944-4-4V4c0-2.056 1.944-4 4-4h8c2.056 0 4 1.944 4 4v8c0 2.056-1.944 4-4 4zM4 2c-.935 0-2 1.065-2 2v8c0 .953 1.047 2 2 2h8c.935 0 2-1.065 2-2V4c0-.935-1.065-2-2-2H4zm8.639 1.281A.96.96 0 1 1 11.28 4.64a.96.96 0 0 1 1.36-1.36zM8 12c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4zm0-6c-1.103 0-2 .897-2 2s.897 2 2 2 2-.897 2-2-.897-2-2-2z" fill-rule="evenodd"></path>
			</g>
			<g id="social-def-twitter" xmlns="http://www.w3.org/2000/svg">
				<path d="M16 3.5c-.6.3-1.2.4-1.9.5.7-.4 1.2-1 1.4-1.8-.6.4-1.3.6-2.1.8-.6-.6-1.5-1-2.4-1-1.7 0-3.2 1.5-3.2 3.3 0 .3 0 .5.1.7-2.7-.1-5.2-1.4-6.8-3.4-.3.5-.4 1-.4 1.7 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.5-.4 0 1.6 1.1 2.9 2.6 3.2-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3.1 2.3-1.1.9-2.5 1.4-4.1 1.4H0c1.5.9 3.2 1.5 5 1.5 6 0 9.3-5 9.3-9.3v-.4c.7-.5 1.3-1.1 1.7-1.8z" fill-rule="evenodd"></path>
			</g>
			<g id="social-def-youtube" xmlns="http://www.w3.org/2000/svg">
				<path d="M15.8 4.8c-.2-1.3-.8-2.2-2.2-2.4C11.4 2 8 2 8 2s-3.4 0-5.6.4C1 2.6.3 3.5.2 4.8 0 6.1 0 8 0 8s0 1.9.2 3.2c.2 1.3.8 2.2 2.2 2.4C4.6 14 8 14 8 14s3.4 0 5.6-.4c1.4-.3 2-1.1 2.2-2.4C16 9.9 16 8 16 8s0-1.9-.2-3.2zM6 11V5l5 3-5 3z" fill-rule="evenodd"></path>
			</g>
			<g id="flag-def-en">
				<defs xmlns="http://www.w3.org/2000/svg"><clipPath id="a"><path fill-opacity=".7" d="M-85.3 0h682.6v512H-85.3z"/></clipPath></defs>
				<g xmlns="http://www.w3.org/2000/svg" clip-path="url(#a)" transform="translate(80) scale(.94)">
					<g stroke-width="1pt"><path fill="#012169" d="M-256 0H768v512H-256z"/><path fill="#fff" d="M-256 0v57.2L653.5 512H768v-57.2L-141.5 0H-256zM768 0v57.2L-141.5 512H-256v-57.2L653.5 0H768z"/><path fill="#fff" d="M170.7 0v512h170.6V0H170.7zM-256 170.7v170.6H768V170.7H-256z"/><path fill="#c8102e" d="M-256 204.8v102.4H768V204.8H-256zM204.8 0v512h102.4V0H204.8zM-256 512L85.3 341.3h76.4L-179.7 512H-256zm0-512L85.3 170.7H9L-256 38.2V0zm606.4 170.7L691.7 0H768L426.7 170.7h-76.3zM768 512L426.7 341.3H503l265 132.5V512z"/></g>
				</g>
			</g>
			<g id="flag-def-it" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" stroke-width="1pt"><path fill="#fff" d="M0 0h640v480H0z"/><path fill="#009246" d="M0 0h213.3v480H0z"/><path fill="#ce2b37" d="M426.7 0H640v480H426.7z"/>
			</g>
		</defs>
	</svg>
</footer>
