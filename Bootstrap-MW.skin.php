<?php
/**
 * Skin file for Bootstrap MW skin.
 *
 * @file
 * @ingroup Skins
 */
class SkinBootstrapMW extends SkinTemplate {
	
	var $skinname = 'bootstrapmw', $stylename = 'bootstrapmw',
	    $template = 'BootstrapMW', $useHeadElement = true;
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( "skins.bootstrapmw" );
	}
}

/**
 * BaseTemplate class for Bootstrap MW skin
 * @ingroup Skins
 */
class BootstrapMW extends BaseTemplate {
	
	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
		
		$this->html( 'headelement' ); ?>
		<header class="header">
			<div class="container">
				<nav class="navbar navbar-default hidden-print" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php
						// Create a link and print the wiki header
						echo '
						<a href="'. $this->data['nav_urls']['mainpage']['href'] .'" class="navbar-brand">
							'. $this->data['sitename'] .'
						</a>';
						?>
					</div>
					<div class="navbar-collapse collapse">
						<div class="navbar-form navbar-right form-inline">
							<?php
								// Print the search box form
								$this->searchBox();
							?>
						</div>
					</div>
				</nav>
			</div>
		</header>
			<main class="wiki">
				<nav class="navbar-wiki">
					<div class="container">
						<?php
							// Print cactions list
							$this->cactions();
						?>
					</div>
				</nav>
				<div class="container">
					<div class="row">
						<section class="col-md-10 page">
							<?php if($this->data['sitenotice']) { ?>
								<div id="alert alert-info"><?php $this->html('sitenotice') ?></div>
							<?php } ?>
							<div class="page-header">
								<h1><?php $this->html('title') ?> <small class="visible-print"><?php $this->msg('tagline') ?></small></h1>
							</div>
							<div class="page">
								<div class="subtitle"><?php $this->html('subtitle') ?></div>
								<?php if($this->data['undelete']) { ?><div class="undelete"><?php $this->html('undelete') ?></div><?php } ?>
								<?php if($this->data['newtalk'] ) { ?><div class="newtalk"><?php $this->html('newtalk')  ?></div><?php } ?>
								<?php if($this->data['showjumplinks']) { ?>
									<div class="jumplinks">
										<?php $this->msg('jumpto') ?><a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>
										<?php $this->msg( 'comma-separator' ) ?><a href="#searchInput"><?php $this->msg('jumptosearch') ?></a>
									</div>
								<?php } ?>
								<?php
									// Content
									$this->html('bodytext');
									if($this->data['catlinks']) { $this->html('catlinks'); };
									if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); };
								?>
								<div class="clearfix"></div>
							</div>
						</section>
						<aside class="col-md-2 sidebar hidden-print">
							<?php
								// Display Personal tools.
								$this->personalTools();
								// Display other Navigation blocks.
								$this->renderPortals( $this->data['sidebar'] );
							?>
						</aside>
					</div>
				</div>
				<footer class="footer">
					<div class="container">
				<?php
					$validFooterIcons = $this->getFooterIcons( "icononly" );
					$validFooterLinks = $this->getFooterLinks( "flat" ); // Additional footer links
					
					if ( count( $validFooterLinks ) > 0 ) {
				?>
						<div class="row">
							<div class="col-md-12">
								<ul class="list">
				<?php
						foreach( $validFooterLinks as $aLink ) {
				?>
									<li class="footer-<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>
				<?php
						}
				?>
									<li><a href="https://github.com/South-Paw/Bootstrap-MW">Bootstrap MW Skin</a></a></li>
								</ul>
							</div>
						</div>
				<?php
					};
				?>
						<div class="row">
				<?php
					foreach ( $validFooterIcons as $blockName => $footerIcons ) {
				?>
								<div class="col-md-6 icons footer-<?php echo htmlspecialchars($blockName); ?>">
				<?php
						foreach ( $footerIcons as $icon ) {
							echo $this->getSkin()->makeFooterIcon( $icon );
						};
				?>
								</div>
				<?php 
					};
				?>
						</div>
					</div>
				</footer>
			</main>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	<?php $this->printTrail(); ?>
	</body>
</html><?php
		wfRestoreWarnings();
	}
	
	/*************************************************************************************************/
	protected function renderPortals($sidebar) {
		if ( !isset( $sidebar['SEARCH'] ) ) $sidebar['SEARCH'] = true;
		if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
		if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;
		
		foreach( $sidebar as $boxName => $content ) {
			if ( $content === false )
				continue;

			if ( $boxName == 'SEARCH' ) {
				// We already have one searchbox in the header.
				//$this->searchBox();
			} elseif ( $boxName == 'TOOLBOX' ) {
				$this->toolbox();
			} elseif ( $boxName == 'LANGUAGES' ) {
				$this->languageBox();
			} else {
				$this->customBox( $boxName, $content );
			}
		}
	}
	
	/**
	 * Prints the search box.
	 */
	function searchBox() {
		global $wgUseTwoButtonsSearchForm;
		?>
		<form action="<?php $this->text('wgScript') ?>" class="searchform">
			<div class="form-group">
				<?php
				echo $this->makeSearchInput(array( "id" => "searchInput", "class" => "form-control" ));
				?>
			</div>
			<div class="form-group">
				<?php
				echo $this->makeSearchButton("go", array( "class" => "btn btn-primary" ));
				?>
			</div>
			<?php
				/*if ($wgUseTwoButtonsSearchForm) {
					echo '<div class="form-group">';
					echo $this->makeSearchButton("fulltext", array( "class" => "btn btn-primary" ));
					echo '</div>';
				} else {
					echo '<div class="form-group">';
					echo '<a href="'. $this->text('searchaction') .'" rel="search" class="btn btn-primary">'. $this->msg('powersearch-legend') .'</a>';
					echo '</div>';
				};*/
			?>
		</form>
	<?php
	}

	/**
	 * Prints the cactions bar.
	 */
	function cactions() {
	?>
		<ul class="nav nav-tabs">
		<?php
		foreach($this->data['content_actions'] as $key => $tab) {
			echo '
			' . $this->makeListItem( $key, $tab );
		}
		?>
		</ul>
<?php
	}

	/**
	 * Prints the personal tools.
	 */
	function personalTools() {
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php $this->msg('personaltools') ?></h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<?php
				foreach($this->getPersonalTools() as $key => $item) {
					echo $this->makeListItem($key, $item);
				}
				?>
			</ul>
		</div>
	</div>
<?php
	}
	
	/*************************************************************************************************/
	function toolbox() {
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php $this->msg('toolbox') ?></h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<?php
				foreach ( $this->getToolbox() as $key => $tbitem ) {
					echo $this->makeListItem($key, $tbitem);
				}
				wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
				wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
				?>
			</ul>
		</div>
	</div>
<?php
	}
	
	/*************************************************************************************************/
	function languageBox() {
		if( $this->data['language_urls'] ) {
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php $this->msg('otherlanguages') ?></h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<?php
				foreach($this->data['language_urls'] as $key => $langlink) {
					echo $this->makeListItem($key, $langlink);
				}
				?>
			</ul>
		</div>
	</div>
<?php
		}
	}

	/*************************************************************************************************/
	function customBox( $bar, $cont ) {
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php $msg = wfMessage( $bar ); echo htmlspecialchars( $msg->exists() ? $msg->text() : $bar ); ?></h3>
		</div>
		<div class="panel-body">
			<?php
			if ( is_array( $cont ) ) { ?>
				<ul class="nav nav-pills nav-stacked">
					<?php
					foreach($cont as $key => $val) {
						echo $this->makeListItem($key, $val);
					} ?>
				</ul>
				<?php
			} else {
				print $cont;
			}?>
		</div>
	</div>
<?php
	}
}
?>