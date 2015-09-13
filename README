Audio Book To Text Sync
=======================

This program is written as an experiment of syncing an audio book to it's text. So we can show a text of this book on a screen and highlight current word.
For this perpose we recognize the audio with [CMU Sphinx](http://cmusphinx.sourceforge.net/):

```bash
pocketsphinx_continuous -infile $name.wav -hmm ./pocketsphinx-0.8/model/hmm/en_US/hub4wsj_sc_8k -lm ./pocketsphinx-0.8/model/lm/en_US/hub4.5000.DMP -backtrace yes -beam 1e-20 -pbeam 1e-20 -lw 2.0  -dict ./pocketsphinx-0.8/model/lm/en_US/hub4.5000.dic -time 1 > $name-parsed.txt
```

After running the command above we have a regignized text in this format:

```bash
the 1540.700000 1540.840000 0.509371
eyes 1540.850000 1541.390000 0.185112
to(2) 1541.400000 1541.590000 0.684926
double 1541.600000 1541.990000 0.963190
blow 1542.000000 1542.520000 0.255136
where(2) 1542.530000 1543.020000 0.024443
...
```

Of course there are many mistakes. And then we run this program to filter them and get result.
