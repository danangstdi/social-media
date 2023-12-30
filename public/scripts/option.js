if (document.getElementById('story_option<?= $story['story_id'] ?>').classList.contains('hidden')) {
  document.getElementById('story_option<?= $story['story_id'] ?>').classList.remove('hidden');
} else {
  document.getElementById('story_option<?= $story['story_id'] ?>').classList.add('hidden');
}