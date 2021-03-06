<?php
/**
 * Skin file for Bootstrap MW skin.
 *
 * @file
 * @ingroup Skins
 */
class SkinBootstrapMW extends SkinTemplate {
	var $skinname = 'bootstrapmw', $stylename = 'bootstrapmw', $template = 'BootstrapMW', $useHeadElement = true;
	
	function setupSkinUserCss(OutputPage $out){
		parent::setupSkinUserCss($out);
		
		$out->addHeadItem('ie-meta', '<meta http-equiv="X-UA-Compatible" content="IE=edge" />');
		$out->addHeadItem('viewport-meta', '<meta name="viewport" content="width=device-width, initial-scale=1" />');
		
		$out->addModuleStyles('skins.bootstrapmw');
	}
}

/**
 * BaseTemplate class for Bootstrap MW skin
 * @ingroup Skins
 */
class BootstrapMW extends BaseTemplate {
	public function execute() {
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
		
		$this->html('headelement'); ?>
		
		<header class="header">
			<div class="container">
				<nav class="navbar navbar-default hidden-print" role="navigation">
					<div class="navbar-header"><?php
						// Create index link for wiki header
						echo '
						<a href="'. $this->data['nav_urls']['mainpage']['href'] .'" class="navbar-brand">
							'. $this->data['sitename'] .'
						</a>'; ?>
					</div>
					<div class="navbar-form navbar-right form-inline"><?php
						// Create a search form for wiki header
						$this->searchBox(); ?>
					</div>
				</nav>
			</div>
		</header>
		<main class="wiki">
			<nav class="navbar-wiki">
				<div class="container"><?php
					// Print content actions nav items
					$this->contentActions(); ?>
				</div>
			</nav>
			<div class="container">
				<div class="row">
					<section class="col-md-10 col-sm-12 page">
						<?php if($this->data['sitenotice']) { ?><div id="alert alert-info"><?php $this->html('sitenotice'); ?></div><?php }; ?>
						<div class="page-header">
							<h1><?php $this->html('title'); ?> <small class="visible-print"><?php $this->msg('tagline'); ?></small></h1>
						</div>
						<div class="page">
							<div class="subtitle"><?php $this->html('subtitle'); ?></div>
							<?php if($this->data['undelete']) { ?><div class="undelete"><?php $this->html('undelete'); ?></div><?php } ?>
							<?php if($this->data['newtalk'] ) { ?><div class="newtalk"><?php $this->html('newtalk');  ?></div><?php } ?>
							<?php if($this->data['showjumplinks']) {
								// Todo: showing these for mobiles to easily navigate ?>
								<div class="jumplinks">
									<?php $this->msg('jumpto'); ?><a href="#column-one"><?php $this->msg('jumptonavigation'); ?></a>
									<?php $this->msg('comma-separator'); ?><a href="#searchInput"><?php $this->msg('jumptosearch'); ?></a>
								</div>
							<?php };
							// Now print the acutal page content
							$this->html('bodytext');
							
							// Print the catagory links for this content
							if($this->data['catlinks']) { $this->html('catlinks'); }
							
							// Print any remaining data after the content
							if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); }?>
							<div class="clearfix"></div>
						</div>
					</section>
					<aside class="col-md-2 hidden-sm hidden-xs sidebar hidden-print"><?php
						// Print the personal tools
						$this->personalTools();
						
						// Print all the other navigation blocks
						$this->renderPortals($this->data['sidebar']); ?>
					</aside>
				</div>
			</div>
			<footer class="footer">
				<div class="container"><?php
					$validFooterIcons = $this->getFooterIcons('icononly');
					$validFooterLinks = $this->getFooterLinks('flat'); // Additional footer links
				
					if ( count( $validFooterLinks ) > 0 ) { ?>
						<div class="row">
							<div class="col-md-12">
								<ul class="list"><?php
									foreach( $validFooterLinks as $aLink ) { ?>
										<li class="footer-<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>
									<?php } ?>
									<li><a href="https://github.com/South-Paw/Bootstrap-MW">Bootstrap MW Skin</a> v1.0</li>
								</ul>
							</div>
						</div>
					<?php }; ?>
					<div class="row"><?php
						foreach ( $validFooterIcons as $blockName => $footerIcons ) { ?>
								<div class="col-md-6 icons footer-<?php echo htmlspecialchars($blockName); ?>"><?php
									foreach ( $footerIcons as $icon ) {
										echo $this->getSkin()->makeFooterIcon( $icon );
									}; ?>
								</div>
						<?php }; ?>
					</div>
				</div>
			</footer>
		</main>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="skins/Bootstrap-MW/js/bootstrap.min.js"></script>
		<?php $this->printTrail(); ?>
	</body>
</html><?php
		wfRestoreWarnings();
	}
	
	/*************************************************************************************************/
	
	/**
	 * Prints all navigation portals
	 */
	protected function renderPortals($sidebar) {
		if (!isset($sidebar['SEARCH'])) $sidebar['SEARCH'] = true;
		if (!isset($sidebar['TOOLBOX'])) $sidebar['TOOLBOX'] = true;
		if (!isset($sidebar['LANGUAGES'])) $sidebar['LANGUAGES'] = true;
		
		foreach($sidebar as $boxName => $content) {
			if ($content === false)
				continue;

			if ($boxName == 'TOOLBOX') {
				$this->toolBox();
			} elseif ($boxName == 'LANGUAGES') {
				$this->languageBox();
			} else {
				$this->customBox($boxName, $content);
			};
		};
	}
	
	/**
	 * For renderPortals()
	 * Print tool box portal
	 */
	function toolBox() { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php $this->msg('toolbox'); ?></h3>
			</div>
			<div class="panel-body">
				<ul class="nav nav-pills nav-stacked"><?php
					foreach ( $this->getToolbox() as $key => $tbitem ) {
						echo $this->makeListItem($key, $tbitem);
					};
					wfRunHooks('MonoBookTemplateToolboxEnd', array(&$this));
					wfRunHooks('SkinTemplateToolboxEnd', array(&$this, true )); ?>
				</ul>
			</div>
		</div><?php
	}
	
	/**
	 * For renderPortals()
	 * Print language box portal
	 */
	function languageBox() {
		if($this->data['language_urls']) { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php $this->msg('otherlanguages'); ?></h3>
				</div>
				<div class="panel-body">
					<ul class="nav nav-pills nav-stacked"><?php
						foreach($this->data['language_urls'] as $key => $langlink) {
							echo $this->makeListItem($key, $langlink);
						}; ?>
					</ul>
				</div>
			</div><?php
		};
	}
	
	/**
	 * For renderPortals()
	 * Print any custom box portal
	 */
	function customBox($bar, $cont) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php $msg = wfMessage( $bar ); echo htmlspecialchars( $msg->exists() ? $msg->text() : $bar ); ?></h3>
			</div>
			<div class="panel-body"><?php
				if ( is_array( $cont ) ) { ?>
					<ul class="nav nav-pills nav-stacked"><?php
						foreach($cont as $key => $val) {
							echo $this->makeListItem($key, $val);
						}; ?>
					</ul><?php
				} else {
					print $cont;
				}; ?>
			</div>
		</div><?php
	}
	
	/*************************************************************************************************/
	
	/**
	 * Prints the search box
	 */
	function searchBox() {
		global $wgUseTwoButtonsSearchForm; ?>
		<form action="<?php $this->text('wgScript'); ?>" class="searchform">
			<div class="form-group"><?php
				echo $this->makeSearchInput(array('id' => 'searchInput', 'class' => 'form-control')); ?>
			</div>
			<div class="form-group"><?php
				echo $this->makeSearchButton('go', array( 'class' => 'btn btn-primary' )); ?>
			</div>
		</form><?php
	}

	/**
	 * Prints the content actions nav items
	 */
	function contentActions() { ?>
		<ul class="nav nav-tabs"><?php
			foreach($this->data['content_actions'] as $key => $tab) {
				echo $this->makeListItem( $key, $tab );
			}; ?>
		</ul><?php
	}

	/**
	 * Prints the personal tools.
	 */
	function personalTools() { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php $this->msg('personaltools'); ?></h3>
			</div>
			<div class="panel-body">
				<ul class="nav nav-pills nav-stacked"><?php
					foreach($this->getPersonalTools() as $key => $item) {
						echo $this->makeListItem($key, $item);
					}; ?>
				</ul>
			</div>
		</div><?php
	}
} ?>
