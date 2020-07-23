<?php

////////////////////////////////////////////////////////////////////////

function theme($theme) {
	if (empty($theme) or $theme === 'random') {
		switch (rand(1, 3)) {
			case 1:
				$theme = 'clair';
				break;
			case 2:
				$theme = 'sombre';
				break;
			case 3:
				$theme = 'solarizeddark';
				break;
		}
	}
	return $theme;
}

?>
