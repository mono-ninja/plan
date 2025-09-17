<?php

namespace Plan;

defined( 'ABSPATH' ) || exit;

class Autoloader {

	private $namespace;
	private $directory;

	public function __construct( $namespace ) {
		$this->namespace = $namespace;
		$this->directory = __DIR__;
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	public function autoload( $class ): void {
		if ( strpos( $class, $this->namespace ) === 0 ) {
			$file = $this->get_file_path( $class );

			if ( $file && file_exists( $file ) ) {
				require_once( $file );

				return;
			}
		}
	}

	public function get_file_path( string $class ): ?string {

		$relativeClass = substr( $class, strlen( $this->namespace ) );

		$file = str_replace( '\\', DIRECTORY_SEPARATOR, $relativeClass ) . '.php';

		$full_path = $this->directory . DIRECTORY_SEPARATOR . $file;

		if ( file_exists( $full_path ) ) {
			return $full_path;
		}


		return null;
	}

}
